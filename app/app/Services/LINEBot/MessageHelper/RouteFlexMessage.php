<?php

namespace App\Services\LINEBot\MessageHelper;

use Illuminate\Support\Facades\Log;
use LINE\LINEBot\Constant\Flex\ComponentFontSize;
use LINE\LINEBot\Constant\Flex\ComponentFontWeight;
use LINE\LINEBot\Constant\Flex\ComponentLayout;
use LINE\LINEBot\Constant\Flex\ComponentMargin;
use LINE\LINEBot\Constant\Flex\ComponentSpacing;
use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\BoxComponentBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\FillerComponentBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\TextComponentBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ContainerBuilder\BubbleContainerBuilder;
use LINE\LINEBot\MessageBuilder\FlexMessageBuilder;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use LINE\LINEBot\QuickReplyBuilder;
use Util_DateTime;

class RouteFlexMessage extends FlexMessageBuilder
{
  private $route;
  private $start_time;

  /**
   * @param Route $route
   * @param QuickReplyBuilder $quickReply
   */
  public function __construct($route, QuickReplyBuilder $quickReply = null)
  {
    $this->route = $route;
    $visit = $this->route->visit;

    $this->start_time = Util_DateTime::createFromYmdHis($visit->start);

    $containerBuilder = BubbleContainerBuilder::builder()
      ->setHeader(self::_headerComponent())
      ->setBody(self::_routeBody());

    $altText = "よみらん";
    parent::__construct($altText,  $containerBuilder, $quickReply);
  }

  /**
   * @return ComponentBuilder
   */
  private function _headerComponent()
  {
    $label = TextComponentBuilder::builder();
    $label->setText("DATE");
    $label->setColor("#ffffff66");
    $label->setSize(ComponentFontSize::XS);

    $text = TextComponentBuilder::builder();
    $text->setText($this->start_time->Ymd());
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

  /**
   * @return ComponentBuilder
   */
  private function _routeBody()
  {
    $facilities = $this->route->facilities;

    $placeComponent[] = TextComponentBuilder::builder()
      ->setText("入園予定時間: " . $this->start_time->format('H:i'))
      ->setSize(ComponentFontSize::XS)
      ->setColor("#b7b7b7");

    foreach ($facilities as $facility) {
      $placeComponent[] = self::_timeAndPlace("00:00", $facility);
    }

    return BoxComponentBuilder::builder()
      ->setLayout(ComponentLayout::VERTICAL)
      ->setContents($placeComponent);
  }


  /**
   * @param string $time `HH:mm`
   * @param Facility $facility
   * @return BoxComponentBuilder
   */
  private function _timeAndPlace($time, $facility)
  {
    $timeComponent = TextComponentBuilder::builder()
      ->setText($time)
      ->setSize(ComponentFontSize::SM);

    $dot = BoxComponentBuilder::builder()
      ->setWidth("12px")
      ->setHeight("12px")
      ->setBorderWidth("2px")
      ->setCornerRadius("30px")
      ->setLayout(ComponentLayout::VERTICAL)
      ->setContents([FillerComponentBuilder::builder()])
      ->setBorderColor("#6486E3");

    $dotComponent = BoxComponentBuilder::builder()
      ->setLayout(ComponentLayout::VERTICAL)
      ->setFlex(0)
      ->setContents([
        FillerComponentBuilder::builder(),
        $dot,
        FillerComponentBuilder::builder(),
      ]);

    $placeComponent = TextComponentBuilder::builder()
      ->setFlex(4)
      ->setText($facility->name)
      ->setSize(ComponentFontSize::SM);

    return BoxComponentBuilder::builder()
      ->setLayout(ComponentLayout::HORIZONTAL)
      ->setSpacing(ComponentSpacing::LG)
      ->setMargin(ComponentMargin::XL)
      ->setContents([$timeComponent, $dotComponent, $placeComponent]);
  }
}
