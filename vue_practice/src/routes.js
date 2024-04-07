import { createWebHistory, createRouter } from 'vue-router';
import AddBook from './components/books/addbooks.vue'; // Assuming AddBook is the correct name and it's in components directory

const routes = [
    {
        path: "/about",
        name: "about",
        component: AddBook // Assuming AddBook is the correct component
    }
]
const router = createRouter({
    history: createWebHistory(),
    routes
})

export default router;