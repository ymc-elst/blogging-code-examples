<?php
namespace Ymc\EzPublishProfilerBundle\DataCollector;

use Symfony\Component\HttpKernel\DataCollector\DataCollector;
use Symfony\Component\HttpKernel\DataCollector\DataCollectorInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use eZ\Bundle\EzPublishCoreBundle\Controller;


class EzPublishDataCollector extends DataCollector
{
    private $container;
    protected $data = array();

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
    /*
     * Overwrite the collect method and fill it
     * with custom EzPublish Stuff
     */
    public function collect(Request $request, Response $response, \Exception $exception = null)
    {
        $this->data = array(
                'ezinfos'   =>  'eZ Publish 5',
                'ezdata'    =>  $this->ezDebugInfo($request->get('contentId')),
           );
    }


    public function getEzInfos()
    {
        return $this->data['ezinfos'];
    }

    public function getEzData()
    {
        return $this->data['ezdata'];
    }

    public function ezDebugInfo($content_id)
    {
        $debugProfilerOutput = "";
        if((int)$content_id > 0)
        {
            if ($this->container->has('YmcEzPublishProfilerBundle')) {
                $debugController =  $this->container->get('YmcEzPublishProfilerBundle');
                $debugController->setContainer($this->container);
                $debugProfilerOutput = $debugController->debugAction($content_id);
                }
        }
        return $debugProfilerOutput;
    }

    public function getName()
    {
        return 'ezpublishdata';
    }
}