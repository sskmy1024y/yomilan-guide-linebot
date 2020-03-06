declare module '*.vue' {
  import Vue, { ComponentOptions } from 'vue'
  const _: ComponentOptions<Vue>
  export { _ as default }
}

declare module '*.json' {
  const _: any
  export { _ as default }
}

declare module 'element-ui'
declare module 'element-ui/lib/locale'
declare module 'element-ui/lib/locale/lang/ja'
