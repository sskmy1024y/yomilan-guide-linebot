<?php

namespace App\Services\LINEBot\MessageHelper;

use LINE\LINEBot\Constant\Flex\ComponentFontSize;
use LINE\LINEBot\Constant\Flex\ComponentFontWeight;
use LINE\LINEBot\Constant\Flex\ComponentLayout;
use LINE\LINEBot\Constant\Flex\ComponentSpacing;
use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\BoxComponentBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\TextComponentBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ContainerBuilder\BubbleContainerBuilder;
use LINE\LINEBot\MessageBuilder\FlexMessageBuilder;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use LINE\LINEBot\QuickReplyBuilder;

class RouteFlexMessage extends FlexMessageBuilder
{
  private $containerBuilder;

  /**
   * @param Route $route
   * @param QuickReplyBuilder $quickReply
   */
  public function __construct($route, QuickReplyBuilder $quickReply = null)
  {
    $this->containerBuilder = BubbleContainerBuilder::builder()
      ->setHeader(self::_getHeader($route->created_at));

    $altText = "よみらん";

    parent::__construct($altText,  $this->containerBuilder, $quickReply);
  }

  /**
   * @param string $date Y-m-dの形式 
   * @return ComponentBuilder
   */
  private function _getHeader($date)
  {
    $label = TextComponentBuilder::builder();
    $label->setText("DATE");
    $label->setColor("#ffffff66");
    $label->setSize(ComponentFontSize::XS);

    $text = TextComponentBuilder::builder();
    $text->setText($date);
    $text->setColor("#ffffff");
    $text->setFlex(4);
    $text->setSize(ComponentFontSize::SM);
    $text->setWeight(ComponentFontWeight::BOLD);

    $dateComponent = BoxComponentBuilder::builder();
    $dateComponent->setLayout(ComponentLayout::VERTICAL);
    $dateComponent->setContents([$label, $text]);

    $titleComponent = TextComponentBuilder::builder();
    $titleComponent->setText("よみうりランド ルート");
    $titleComponent->setColor("#ffffff");
    $titleComponent->setSize(ComponentFontSize::LG);
    $titleComponent->setWeight(ComponentFontWeight::BOLD);

    $headerComponentBuilder = BoxComponentBuilder::builder();
    $headerComponentBuilder->setLayout(ComponentLayout::VERTICAL);
    $headerComponentBuilder->setPaddingAll(ComponentSpacing::LG);
    $headerComponentBuilder->setBackgroundColor('#0367D3');
    $headerComponentBuilder->setContents([$dateComponent, $titleComponent]);

    return $headerComponentBuilder;
  }
}
