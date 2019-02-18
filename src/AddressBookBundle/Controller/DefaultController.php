<?php

namespace AddressBookBundle\Controller;

use AddressBookBundle\Entity\AddressBook;
use AddressBookBundle\Form\SearchType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class DefaultController
 * @package AddressBookBundle\Controller
 */
class DefaultController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * Return the index page
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $addressbook = $em->getRepository(AddressBook::class)->Count();
        if ($addressbook[0][1] < 1)
            return $this->redirect($this->generateUrl('address_book_add'));

        $countAddress = $addressbook[0][1];
        $countCitys = $em->getRepository(AddressBook::class)->CountCitys();
        $countCitys = $countCitys[0][1];
        return $this->render('@AddressBook\Default\index.html.twig', ['countAddress' => $countAddress, 'countCitys'=>$countCitys]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * Return only the Sidebar
     */
    public function SideBarAction()
    {
        return $this->render('@AddressBook\Default\sidebar.html.twig');
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * Return Only the Nav bar
     */
    public function NavBarAction()
    {
        $form = $this->createForm(SearchType::class);
        return $this->render('@AddressBook\Default\navbar.html.twig', ['form'=>$form->createView()]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * Error page when an error is true
     */
    public function ErrorAction()
    {
        return $this->render('@AddressBook\Default\error.html.twig');
    }
}
