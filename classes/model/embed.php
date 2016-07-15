<?php

namespace Foolz\FoolFuuka\Plugins\MediaEmbed\Model;

use Foolz\FoolFuuka\Model\Comment;

class Embed
{ 
  public static function filter($result)
  {
    $data = $result->getObject();
    if(!$data->radix->getValue('plugin_mediaembed_enable'))
    {
      return null;
    }
    $data->comment->comment = preg_replace_callback('/(\[(youtube)\=(.*)\/\])/i', 
      function($hit)
      {
        switch ($hit[2]) {
          case "youtube":
            $srcurl = "https://www.youtube.com/embed/".$hit[3];
            break;
          //you may add another video provider here
          default:
            return "";  //user fucked up the bbcode
        }
        return "<iframe width=\"640\" height=\"480\" frameborder=\"0\" allowfullscreeen scrolling=\"no\" src=\"".$srcurl."\"></iframe>";
      },
      $data->comment->comment); 
  }
}
