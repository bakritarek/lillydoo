<?php

namespace AddressBookBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AddressBookController extends Controller
{
    public function AddAddressAction() {

        return $this->render('@AddressBook\AddressBook\add.html.twig');
    }
}
