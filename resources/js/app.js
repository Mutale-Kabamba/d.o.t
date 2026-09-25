import './bootstrap';
import { initWorksheet } from './worksheet';

function runWorksheetIfPresent() {
    if (document.getElementById('worksheet-form') || document.getElementById('stepper-list')) {
        initWorksheet();
    }
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', runWorksheetIfPresent);
} else {
    runWorksheetIfPresent();
}
