<?php

namespace AddressBookBundle\Controller;

use AddressBookBundle\Entity\AddressBook;
use AddressBookBundle\Form\AddressBookType;
use AddressBookBundle\Form\SearchType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class AddressBookController
 * @package AddressBookBundle\Controller
 */
class AddressBookController extends Controller
{
    /**
     * @return Response
     * Create a new Address Book
     */
    public function AddAction() {

        $form = $this->createForm(AddressBookType::class);

        return $this->render('@AddressBook\AddressBook\add.html.twig', ['form'=>$form->createView()]);
    }

    /**
     * @param $id
     * @return Response
     *
     * Update an Address Book
     */
    public function UpdateAction($id) {

        $em = $this->getDoctrine()->getManager();

        $addressebook = $em->getRepository(AddressBook::class)->find($id);
        $form = $this->createForm(AddressBookType::class, $addressebook);

        return $this->render('@AddressBook\AddressBook\update.html.twig', ['form'=>$form->createView(), 'addressbook'=>$addressebook]);
    }

    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * Delete an Address Book
     */
    public function DeleteAction($id) {
        $em = $this->getDoctrine()->getManager();

        $addressbook = $em->getRepository(AddressBook::class)->find($id);

        $em->remove($addressbook);

        $em->flush();

        return $this->redirect($this->generateUrl('address_book_display_all'));
    }

    /**
     * @param $id
     * @param Request $request
     * @return JsonResponse|\Symfony\Component\HttpFoundation\RedirectResponse
     *
     * Store an Address Book in DataBase
     */
    public function StoreAction($id, Request $request) {

        $em = $this->getDoctrine()->getManager();

        if ($id === 0)
            $AddressBook = new AddressBook();
        else
            $AddressBook = $em->getRepository(AddressBook::class)->find($id);


        $form = $this->createForm(AddressBookType::class, $AddressBook);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $AddressBook = $form->getData();
            $file = $AddressBook->getFile();
            $fileName = $this->generateUniqueFileName().'.'.$file->guessExtension();

            try {
                $file->move(
                    $this->getParameter('pictures_directory'),
                    $fileName
                );
            } catch (FileException $e) {
                $response = new JsonResponse();

                return $response->setData($e->getMessage());
            }

            $AddressBook->setPicture($fileName);

            $em->persist($AddressBook);
            $em->flush();
            return $this->redirect($this->generateUrl('address_book_display_all'));


        }

        return $this->redirect($this->generateUrl('address_book_error'));

    }

    /**
     * @return string
     *
     * Generate an uniq name to be used like a picture name
     */
    private function generateUniqueFileName()
    {
        return md5(uniqid());
    }

    /**
     * @return Response
     *
     * Display all Address Book
     */
    public function DisplayAllAction() {
        $em = $this->getDoctrine()->getManager();

        $AddressBooks = $em->getRepository(AddressBook::class)->findAll();

        return $this->render('@AddressBook/AddressBook/display_all.html.twig', ['AddressBooks'=>$AddressBooks]);
    }

    /**
     * @param Request $request
     * @return Response
     *
     * Display only Address Book who match with the word in search bar
     */
    public function DisplayResearchAction(Request $request) {
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(SearchType::class);
        $form->handleRequest($request);
        $word = $form['word']->getData();
        $addressbooks = $em->getRepository(AddressBook::class)->Search($word);


        return $this->render('@AddressBook/AddressBook/display_research.html.twig', ['AddressBooks' => $addressbooks]);
    }
}
