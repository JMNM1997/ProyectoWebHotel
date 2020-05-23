<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Entity\User;
use App\Entity\Cliente;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/register", name="app_register")
     */
    public function register(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/register.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \Exception('This method can be blank - it will be intercepted by the logout key on your firewall');
    }


    /**
     * @Route("/usuario/crear", name="crear_usuario")
     *
     * @param UserPasswordEncoderInterface $encoder
     * @return void
     */
    public function crearUsuario(UserPasswordEncoderInterface $encoder)
    {
        $usuario = new User();
        $contrasena = "1234";
        $encoded = $encoder->encodePassword($usuario, $contrasena);
        $usuario->setPassword($encoded);
        $usuario->setEmail("admin@es.es");
        $em = $this->getDoctrine()->getManager();
        $em->persist($usuario);
        $em->flush();

        return $this->redirectToRoute("inicio");
    }

    /**
     * @Route("/usuario/crearCliente", name="crear_cliente")
     *
     * @param UserPasswordEncoderInterface $encoder
     * @return void
     */
    public function crearCliente(UserPasswordEncoderInterface $encoder, AuthenticationUtils $authenticationUtils)
    {

        //primero el usuario en la bd
        $usuario = new User();

        $email = $_POST['email'];
        $password = $_POST['password'];

        //datos cliente

        $dni = $_POST['dni'];
        $nombre = $_POST['nombre'];
        $apellidos = $_POST['apellidos'];
        $telefono = $_POST['telefono'];


        //vamos a comprobar campos nulos para no crear un usuario vacio
        if (($email == null) || ($password == null) || ($dni == null) || ($nombre == null) || ($apellidos == null) || ($telefono == null)) {
            //seria conveniente poner un error antes
            return $this->redirectToRoute("error_registro");
        }

        $encoded = $encoder->encodePassword($usuario, $password);
        $usuario->setPassword($encoded);
        $usuario->setEmail($email);


        $em = $this->getDoctrine()->getManager();
        $em->persist($usuario);
        $em->flush();


        //y una vez rellenado completamos los campos de cliente
        $cliente = new Cliente();


        $cliente->setDni($dni);
        $cliente->setNombre($nombre);
        $cliente->setApellidos($apellidos);
        $cliente->setTelefono($telefono);
        $cliente->setUser($usuario);

        if ($nombre == null) {
            return $this->redirectToRoute("inicio");
        }
        $em = $this->getDoctrine()->getManager();
        $em->persist($cliente);
        $em->flush();


        return $this->redirectToRoute("inicio");
    }

    /**
     * @Route("/usuario/errorRegistro", name="error_registro")
     *
     * @param UserPasswordEncoderInterface $encoder
     * @return void
     */
    public function errorRegistro()
    {
        //primero lo haremos sin pasarle las variables de error, luego las implementaremos
        return $this->render('security/error_registro.html.twig');
    }
}
