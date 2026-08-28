import './bootstrap';
import { initWorksheet } from './worksheet';

// Execute immediately if DOM is already ready, or listen to DOMContentLoaded
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        initWorksheet();
    });
} else {
    initWorksheet();
}
