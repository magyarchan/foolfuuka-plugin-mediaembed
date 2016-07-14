<?php

use Foolz\FoolFrame\Model\Autoloader;
use Foolz\FoolFrame\Model\Context;
use Foolz\Plugin\Event;

class HHVM_MediaEmbed
{
    public function run()
    {
        Event::forge('Foolz\Plugin\Plugin::execute#foolz/foolfuuka-plugin-mediaembed')
            ->setCall(function($result) {

                /* @var Context $context */
                $context = $result->getParam('context');
                /** @var Autoloader $autoloader */
                $autoloader = $context->getService('autoloader');

                $autoloader->addClass('Foolz\FoolFuuka\Plugins\MediaEmbed\Model\Embed', __DIR__.'/classes/model/embed.php');

                Event::forge('Foolz\FoolFuuka\Model\Comment::processComment#var.processedComment')
                    ->setCall('Foolz\FoolFuuka\Plugins\MediaEmbed\Model\Embed::filter')
                    ->setPriority(4);

		/*
                Event::forge('Foolz\FoolFuuka\Model\CommentInsert::insert#obj.afterInputCheck')
                    ->setCall('Foolz\FoolFuuka\Plugins\MediaEmbed\Model\Embed::infilter')
                    ->setPriority(4); */


                Event::forge('Foolz\FoolFuuka\Model\RadixCollection::structure#var.structure')
                    ->setCall(function($result) {
                        $structure = $result->getParam('structure');
                        $structure['plugin_embed_enable'] = [ //fuck I'm too wasted to make this right this time
                            'database' => true,
                            'boards_preferences' => true,
                            'type' => 'checkbox',
                            'help' => _i('Enable media embed')
                        ];
                        $result->setParam('structure', $structure)->set($structure);
                    })->setPriority(4);
            });
    }
}

(new HHVM_MediaEmbed())->run();
