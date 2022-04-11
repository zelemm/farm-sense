export default (asyncView) => () => import(`../components/${asyncView}.vue`)
