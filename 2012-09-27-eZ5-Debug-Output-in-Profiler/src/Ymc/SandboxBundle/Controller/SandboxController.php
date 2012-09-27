<?php
/*
 * A Sandbox Controller with one little
 * action to call a simple Website
 * with a layout arround
 */
namespace Ymc\SandboxBundle\Controller;

use eZ\Bundle\EzPublishCoreBundle\Controller;
use Symfony\Component\HttpFoundation\Request;

/*
 * the action to render the template
 * with the given eZ Publish 5 content
 */
class SandboxController extends Controller
{
    public function sandboxAction($contentId)
    {
        return $this->render(
            "ymcSandboxBundle::ezcontent.html.twig",
            array(
                "content" => $this->getRepository()->getContentService()->loadContent($contentId)
            )
        );
    }
}
