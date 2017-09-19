import router from './routes';

router.beforeResolve(function (to, from, next) {
    if(to.matched.some(record => record.meta.auth)){
        if(User === null)
            window.location.assign(route('login'));
        else
            next();
    }
    next();
});