<?php
/**
 * ezDebug controller
 *
 * @access public
 * @author ymc-elst <elena.steingrad@ymc.ch>
 * @license ymc standard license <license@ymc.ch>
 * @since 2012/09/24
 */

namespace Ymc\EzPublishProfilerBundle\Controller;

use eZ\Bundle\EzPublishCoreBundle\Controller;
use \ezDebug;

use \ezpKernel;

class EzPublishDebugController extends Controller
{
    public function getName()
    {
        return 'ezpublishdebug';
    }

    public function debugAction($contentId=0)
    {
        $debugOutput = "Content ID is unknown";

        if( (int)$contentId > 0)
        {
        $debugOutput = $this->getLegacyKernel()->runCallback(

            function () use ($contentId)
            {
                $ezDebugInstance = eZDebug::instance();
                $report = $ezDebugInstance->printReportInternal(true, true, false, true, true, false);
                //just to show what the parameters of printReportInternal mean
                //printReportInternal( $as_html, $returnReport, $allowedDebugLevels, $useAccumulators, $useTiming, $useIncludedFiles )
                return $report;
            });
        }
        // Rendering with twig, embedding the result of a legacy template
        return $this->render(
            "YmcEzPublishProfilerBundle:Controller:ezpublishdebug.html.twig",
            array(
                "title" => "eZ Publish Debug",
                "debugOutput" => $debugOutput,
                "content" => $this->getRepository()->getContentService()->loadContent($contentId)
                )
                );

        }
}