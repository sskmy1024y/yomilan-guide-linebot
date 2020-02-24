<?php

namespace App\Services\LINEBot\MessageHelper;

use App\Models\Route;
use App\Models\FacilityType;
use App\Models\Location;
use App\Services\LINEBot\RouteHelper;
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
use LINE\LINEBot\QuickReplyBuilder;
use Util_DateTime;

class RouteFlexMessageBuilder extends FlexMessageBuilder
{
  /** @var Route */
  private $route;

  /** @var ExDateTimeImmutable */
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
    $label = TextComponentBuilder::builder()
      ->setText("DATE")
      ->setColor("#ffffff66")
      ->setSize(ComponentFontSize::XS);

    $text = TextComponentBuilder::builder()
      ->setText($this->start_time->Ymd())
      ->setColor("#ffffff")
      ->setFlex(4)
      ->setSize(ComponentFontSize::SM)
      ->setWeight(ComponentFontWeight::BOLD);

    $dateComponent = BoxComponentBuilder::builder()
      ->setLayout(ComponentLayout::VERTICAL)
      ->setContents([$label, $text]);

    $titleComponent = TextComponentBuilder::builder()
      ->setText("よみうりランド ルート")
      ->setColor("#ffffff")
      ->setSize(ComponentFontSize::LG)
      ->setWeight(ComponentFontWeight::BOLD);

    $headerComponentBuilder = BoxComponentBuilder::builder()
      ->setLayout(ComponentLayout::VERTICAL)
      ->setPaddingAll(ComponentSpacing::XL)
      ->setPaddingBottom(ComponentSpacing::LG)
      ->setBackgroundColor('#0367D3')
      ->setContents([$dateComponent, $titleComponent]);

    return $headerComponentBuilder;
  }

  /**
   * @return ComponentBuilder
   */
  private function _routeBody()
  {
    $facilities = $this->route->facilities;

    $placeComponent[] = TextComponentBuilder::builder()
      ->setText("入園予定時間: " . $this->start_time->Hi())
      ->setSize(ComponentFontSize::XS)
      ->setColor("#b7b7b7");

    $timestamp = clone $this->start_time;
    foreach ($facilities as $key => $facility) {
      if ($key === 0) {
        $dist = RouteHelper::orbitTime([$facility], Location::enterance());
      } else {
        $dist = RouteHelper::orbitTime([$facilities[$key - 1], $facility]);
      };
      $placeComponent[] = self::_timeAndPlace($timestamp->addMinutes($dist)->Hi(), $facility);
    }

    return BoxComponentBuilder::builder()
      ->setLayout(ComponentLayout::VERTICAL)
      ->setPaddingTop(ComponentSpacing::LG)
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

    $dot_color = "#DDDDDD";
    if ($facility->type == FacilityType::ATTRACTION) {
      $dot_color = "#6486E3";
    } else if ($facility->type == FacilityType::RESTAURANT) {
      $dot_color = "#FF8946";
    }

    $dot = BoxComponentBuilder::builder()
      ->setWidth("12px")
      ->setHeight("12px")
      ->setBorderWidth("2px")
      ->setCornerRadius("30px")
      ->setLayout(ComponentLayout::VERTICAL)
      ->setContents([FillerComponentBuilder::builder()])
      ->setBorderColor($dot_color);

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
