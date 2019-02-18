<?php

namespace AddressBookBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('@AddressBook\Default\index.html.twig');
    }

    public function SideBarAction()
    {
        return $this->render('@AddressBook\Default\sidebar.html.twig');
    }

    public function NavBarAction()
    {
        return $this->render('@AddressBook\Default\navbar.html.twig');
    }
}
