function CmMainShowElements(elements) {
    const list = elements instanceof Element ? [elements] : Array.from(elements || []);
    list.forEach((element) => {
        element.style.transition = '0.35s ease-in-out';
        element.style.display = 'flex';
        element.style.opacity = '1';
    });
}

function CmMainSlideDown(element, duration = 400) {
    if (!element) return;

    element.style.removeProperty('display');
    if (getComputedStyle(element).display === 'none') {
        element.style.display = 'block';
    }

    element.style.overflow = 'hidden';
    element.style.maxHeight = '0px';
    element.style.opacity = '0';
    element.offsetHeight;
    element.style.transition = `max-height ${duration}ms ease, opacity ${duration}ms ease`;
    element.style.maxHeight = `${element.scrollHeight}px`;
    element.style.opacity = '1';

    window.setTimeout(() => {
        element.style.removeProperty('max-height');
        element.style.removeProperty('overflow');
        element.style.removeProperty('transition');
        element.style.removeProperty('opacity');
    }, duration);
}

function CmMainSlideUp(element, duration = 400) {
    if (!element || getComputedStyle(element).display === 'none') return;

    element.style.overflow = 'hidden';
    element.style.maxHeight = `${element.scrollHeight}px`;
    element.offsetHeight;
    element.style.transition = `max-height ${duration}ms ease, opacity ${duration}ms ease`;
    element.style.maxHeight = '0px';
    element.style.opacity = '0';

    window.setTimeout(() => {
        element.style.display = 'none';
        element.style.removeProperty('max-height');
        element.style.removeProperty('overflow');
        element.style.removeProperty('transition');
        element.style.removeProperty('opacity');
    }, duration);
}

function CmMainEscapeRegExp(value) {
    return value.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
}

document.addEventListener('DOMContentLoaded', () => {
    const tabCars = Array.from(document.getElementsByClassName('CmTabSelManuf'));

    if (tabCars.length) {
        tabCars[0].classList.add('CmActiveTabManuf');
        const firstBlock = document.getElementById(tabCars[0].dataset.name);
        if (firstBlock) {
            firstBlock.style.display = 'grid';
        }

        if (tabCars.length < 2) {
            tabCars[0].style.display = 'none';
        }

        tabCars.forEach((tab) => {
            tab.addEventListener('click', () => {
                tabCars.forEach((item) => item.classList.remove('CmActiveTabManuf'));
                tab.classList.add('CmActiveTabManuf');

                const elemId = tab.dataset.name;
                Array.from(document.getElementsByClassName('CmManufContBlock')).forEach((block) => {
                    block.style.display = block.id === elemId ? 'grid' : 'none';
                });
            });
        });
    }

    document.querySelectorAll('.showAllSect').forEach((button) => {
        button.addEventListener('click', (event) => {
            event.preventDefault();

            const showNextId = button.getAttribute('showLNext');
            const hiddenList = showNextId ? document.getElementById(showNextId) : null;
            const box = button.closest('.boxSect_x');

            button.style.display = 'none';
            if (hiddenList) {
                hiddenList.style.width = '100%';
                CmMainSlideDown(hiddenList);
            }
            if (box) {
                box.style.boxShadow = '0px 0px 10px 1px #d0d0d0';
                box.addEventListener('mouseleave', () => {
                    if (hiddenList) {
                        CmMainSlideUp(hiddenList);
                    }
                    window.setTimeout(() => {
                        button.style.display = '';
                    }, 500);
                    box.style.boxShadow = 'none';
                }, { once: true });
            }
        });
    });

    document.querySelectorAll('.hideAllSect').forEach((button) => {
        button.addEventListener('click', (event) => {
            event.preventDefault();

            const hiddenList = button.closest('.CmListNSectBl');
            const box = button.closest('.boxSect_x');
            if (hiddenList) {
                CmMainSlideUp(hiddenList);
            }
            if (box) {
                box.style.boxShadow = 'none';
                const showButton = box.querySelector('.showAllSect');
                if (showButton) {
                    showButton.style.display = '';
                }
            }
        });
    });

    document.querySelectorAll('.butAllSec').forEach((button) => {
        button.addEventListener('click', () => {
            CmMainShowElements(document.querySelectorAll('.boxSect_x'));
            button.style.display = 'none';
        });
    });

    const inputSect = document.querySelector('.CmInputSect');
    const clearButton = document.querySelector('.clearButt');
    const sectionBoxes = Array.from(document.querySelectorAll('.boxSect_x'));

    function resetSectionFilter() {
        sectionBoxes.forEach((box) => {
            box.style.display = '';
            box.classList.add('boxSel_x');
            box.querySelectorAll('.nameSect_x, .CmListSectBl li, .CmListNSectBl li').forEach((item) => {
                item.style.display = '';
            });
            box.querySelectorAll('.CmListNSectBl').forEach((list) => {
                list.style.display = 'none';
                list.classList.remove('f_sec_block');
            });
            box.querySelectorAll('.showAllSect').forEach((button) => {
                button.style.display = '';
            });
        });
    }

    function applySectionFilter() {
        if (!inputSect) return;

        const rawValue = inputSect.value.trim();
        const term = rawValue.toLowerCase();

        if (clearButton) {
            clearButton.style.display = rawValue.length ? 'block' : 'none';
        }

        if (term.length < 3) {
            resetSectionFilter();
            return;
        }

        const escapedTerm = CmMainEscapeRegExp(term);
        const termRegex = new RegExp(escapedTerm, 'i');
        const wordRegex = new RegExp(`(^|\\s)${escapedTerm}`, 'i');

        sectionBoxes.forEach((box) => {
            const title = box.querySelector('.nameSect_x');
            const mainItems = Array.from(box.querySelectorAll('.CmListSectBl li'));
            const hiddenItems = Array.from(box.querySelectorAll('.CmListNSectBl li'));
            const hiddenBlock = box.querySelector('.CmListNSectBl');
            const showButton = box.querySelector('.showAllSect');

            const titleMatch = title ? termRegex.test(title.textContent) : false;
            let hasVisibleMatch = titleMatch;
            let hasHiddenMatch = false;

            if (title) {
                title.style.display = titleMatch ? '' : 'none';
            }

            mainItems.forEach((item) => {
                const match = wordRegex.test(item.textContent);
                item.style.display = match || titleMatch ? '' : 'none';
                hasVisibleMatch = hasVisibleMatch || match;
            });

            hiddenItems.forEach((item) => {
                const match = wordRegex.test(item.textContent);
                item.style.display = match || titleMatch ? '' : 'none';
                hasHiddenMatch = hasHiddenMatch || match;
            });

            if (hiddenBlock) {
                if (hasHiddenMatch && !titleMatch) {
                    hiddenBlock.style.display = 'block';
                    hiddenBlock.classList.add('f_sec_block');
                    if (showButton) {
                        showButton.style.display = 'none';
                    }
                } else {
                    hiddenBlock.style.display = 'none';
                    hiddenBlock.classList.remove('f_sec_block');
                    if (showButton) {
                        showButton.style.display = titleMatch ? '' : 'none';
                    }
                }
            }

            if (hasVisibleMatch || hasHiddenMatch) {
                box.style.display = '';
                box.classList.add('boxSel_x');
            } else {
                box.style.display = 'none';
                box.classList.remove('boxSel_x');
            }
        });
    }

    if (inputSect) {
        inputSect.addEventListener('input', applySectionFilter);
        inputSect.addEventListener('keyup', applySectionFilter);
    }

    if (clearButton) {
        clearButton.addEventListener('click', () => {
            clearButton.style.display = 'none';
            if (inputSect) {
                inputSect.value = '';
            }
            resetSectionFilter();
        });
    }
});
