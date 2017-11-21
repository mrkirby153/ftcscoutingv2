import router from './routes';
import state from '../vuex/state'
import {store} from '../vuex/vuex'
import {SET_EDITING_QUESTION} from "../vuex/mutationTypes";

router.beforeResolve(function (to, from, next) {
    if (to.matched.some(record => record.meta.auth)) {
        if (User === null)
            window.location.assign(route('login'));
        else
            next();
    }
    next();
});

router.beforeResolve(function (to, from, next) {
    if (from.name === "survey.edit") {
        if(to.name === "pin.edit"){
            next();
            return;
        }
        if (state.editingQuestion !== null) {
            if (confirm("You have unsaved changes. Navigating away will lose them")) {
                next();
                store.commit(SET_EDITING_QUESTION, null);
            } else {
                next(false);
            }
        }
    } else {
        next();
    }
});