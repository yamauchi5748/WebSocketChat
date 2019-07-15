import VueRouter from 'vue-router';
import PageChat from './components/PageChat.vue';
import PageFrends from './components/PageFrends.vue';
const routes = [
    {path:'/frends',component:PageFrends},
    {path:'/chat',component:PageChat},
    //{path:'/timeline',component:PageTimeline},
    //{path:'/other',component:PageOther},
]
const router = new VueRouter({
    routes
})
export default router;