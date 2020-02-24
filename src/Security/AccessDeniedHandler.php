<?php
namespace App\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;

class AccessDeniedHandler implements AccessDeniedHandlerInterface
{
    private $templates;
    public function __construct(\Twig\Environment $templating)
    {
        $this->templates=$templating;
    }

    public function handle(Request $request, AccessDeniedException $accesDeniedExcception)
    {
        $error=$accesDeniedExcception->getMessage();
        $response=new Response($this->templates->render('shared/error.html.twig',
        ["error_mensaje"=>$error]));
        $response->send();
        exit;
    }
     
}