export enum Direction {
  Top = 'top',
  Left = 'left'
}

/**
 * ScrollTo animation using pure javascript and no jquery
 *
 * @see https://gist.github.com/andjosh/6764939
 *
 * @param element
 * @param to
 * @param duration
 * @param direction
 */
export function scrollTo(
  element: HTMLElement,
  to: number,
  duration: number,
  direction: Direction = Direction.Left
) {
  const start = direction === Direction.Top ? element.scrollTop : element.scrollLeft,
    change = to - start,
    increment = 20
  let currentTime = 0

  const animateScroll = function() {
    currentTime += increment
    var val = easeInOutQuad(currentTime, start, change, duration)
    direction === Direction.Top
      ? (element.scrollTop = val)
      : (element.scrollLeft = val)
    if (currentTime < duration) {
      setTimeout(animateScroll, increment)
    }
  }
  animateScroll()
}

/**
 * @param t current time
 * @param b start value
 * @param c change in value
 * @param d duration
 */
const easeInOutQuad = function(t: number, b: number, c: number, d: number) {
  t /= d / 2
  if (t < 1) return (c / 2) * t * t + b
  t--
  return (-c / 2) * (t * (t - 2) - 1) + b
}
