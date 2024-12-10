class UltrapressTutorial {
    constructor() {
        console.log('UltrapressTutorial: Initializing...');
        this.currentStep = 0;
        this.tutorials = [
            {
                title: 'مرحباً بك في Ultrapress',
                content: 'سنقوم بإرشادك خطوة بخطوة لإنشاء أول دارة لك',
                position: 'center',
                highlight: null
            },
            {
                title: 'إنشاء دارة جديدة',
                content: 'انقر نقراً مزدوجاً في أي مكان فارغ لإضافة مكونة جديدة',
                position: 'center',
                highlight: '.workspace-area'
            },
            {
                title: 'اختيار المكونة',
                content: 'اختر المكونة المناسبة من القائمة. كل مكونة لها وظيفة محددة',
                position: 'right',
                highlight: '.component-list'
            },
            {
                title: 'ربط المكونات',
                content: 'اسحب خطاً من نقطة الإخراج (الحمراء) إلى نقطة الإدخال (الخضراء) للمكونة التالية',
                position: 'bottom',
                highlight: '.connection-points'
            },
            {
                title: 'تكوين المعاملات',
                content: 'انقر على الخط الأزرق لتكوين كيفية نقل البيانات بين المكونات',
                position: 'right',
                highlight: '.connection-line'
            }
        ];

        try {
            this.init();
            console.log('UltrapressTutorial: Initialization complete');
        } catch (error) {
            console.error('UltrapressTutorial: Error during initialization:', error);
        }
    }

    init() {
        console.log('UltrapressTutorial: Checking if tutorial should be shown...');
        if (this.shouldShowTutorial()) {
            console.log('UltrapressTutorial: Creating popup...');
            this.createPopup();
            console.log('UltrapressTutorial: Showing first step...');
            this.showStep(0);
        } else {
            console.log('UltrapressTutorial: Tutorial already completed');
        }
    }

    shouldShowTutorial() {
        const completed = localStorage.getItem('ultrapressTutorialCompleted');
        console.log('UltrapressTutorial: Tutorial completed status:', completed);
        return !completed;
    }

    createPopup() {
        try {
            console.log('UltrapressTutorial: Creating popup element...');
            const popup = document.createElement('div');
            popup.className = 'tutorial-popup active'; 
            popup.style.display = 'block'; 
            popup.innerHTML = `
                <div class="tutorial-popup-title"></div>
                <div class="tutorial-popup-content"></div>
                <div class="tutorial-popup-buttons">
                    <button class="tutorial-button tutorial-button-prev">السابق</button>
                    <button class="tutorial-button tutorial-button-skip">تخطي</button>
                    <button class="tutorial-button tutorial-button-next">التالي</button>
                </div>
            `;
            document.body.appendChild(popup);
            console.log('UltrapressTutorial: Popup created and added to DOM');

            this.popup = popup;
            this.bindEvents();
        } catch (error) {
            console.error('UltrapressTutorial: Error creating popup:', error);
        }
    }

    bindEvents() {
        try {
            console.log('UltrapressTutorial: Binding events...');
            const nextBtn = this.popup.querySelector('.tutorial-button-next');
            const prevBtn = this.popup.querySelector('.tutorial-button-prev');
            const skipBtn = this.popup.querySelector('.tutorial-button-skip');

            if (!nextBtn || !prevBtn || !skipBtn) {
                throw new Error('Tutorial buttons not found');
            }

            nextBtn.addEventListener('click', () => {
                console.log('UltrapressTutorial: Next button clicked');
                this.nextStep();
            });
            prevBtn.addEventListener('click', () => {
                console.log('UltrapressTutorial: Previous button clicked');
                this.prevStep();
            });
            skipBtn.addEventListener('click', () => {
                console.log('UltrapressTutorial: Skip button clicked');
                this.endTutorial();
            });
            console.log('UltrapressTutorial: Events bound successfully');
        } catch (error) {
            console.error('UltrapressTutorial: Error binding events:', error);
        }
    }

    showStep(step) {
        try {
            console.log(`UltrapressTutorial: Showing step ${step}`);
            const tutorial = this.tutorials[step];
            if (!tutorial) {
                throw new Error(`Tutorial step ${step} not found`);
            }

            this.currentStep = step;
            this.updatePopupContent(tutorial);
            this.positionPopup(tutorial.position);
            this.highlightElement(tutorial.highlight);
            this.updateButtons();
            console.log(`UltrapressTutorial: Step ${step} shown successfully`);
        } catch (error) {
            console.error('UltrapressTutorial: Error showing step:', error);
        }
    }

    updatePopupContent(tutorial) {
        try {
            console.log('UltrapressTutorial: Updating popup content');
            const titleEl = this.popup.querySelector('.tutorial-popup-title');
            const contentEl = this.popup.querySelector('.tutorial-popup-content');
            
            if (!titleEl || !contentEl) {
                throw new Error('Popup elements not found');
            }

            titleEl.textContent = tutorial.title;
            contentEl.textContent = tutorial.content;
            console.log('UltrapressTutorial: Content updated successfully');
        } catch (error) {
            console.error('UltrapressTutorial: Error updating content:', error);
        }
    }

    positionPopup(position) {
        try {
            console.log(`UltrapressTutorial: Positioning popup - ${position}`);
            this.popup.style.top = '50%';
            this.popup.style.left = '50%';
            this.popup.style.transform = 'translate(-50%, -50%)';
            console.log('UltrapressTutorial: Popup positioned successfully');
        } catch (error) {
            console.error('UltrapressTutorial: Error positioning popup:', error);
        }
    }

    highlightElement(selector) {
        try {
            console.log(`UltrapressTutorial: Highlighting element - ${selector}`);
            document.querySelectorAll('.highlight-element').forEach(el => {
                el.classList.remove('highlight-element');
            });

            if (selector) {
                const element = document.querySelector(selector);
                if (element) {
                    element.classList.add('highlight-element');
                }
            }
            console.log('UltrapressTutorial: Element highlighted successfully');
        } catch (error) {
            console.error('UltrapressTutorial: Error highlighting element:', error);
        }
    }

    updateButtons() {
        try {
            console.log('UltrapressTutorial: Updating buttons');
            const prevBtn = this.popup.querySelector('.tutorial-button-prev');
            const nextBtn = this.popup.querySelector('.tutorial-button-next');

            prevBtn.style.display = this.currentStep === 0 ? 'none' : 'block';
            nextBtn.textContent = this.currentStep === this.tutorials.length - 1 ? 'إنهاء' : 'التالي';
            console.log('UltrapressTutorial: Buttons updated successfully');
        } catch (error) {
            console.error('UltrapressTutorial: Error updating buttons:', error);
        }
    }

    nextStep() {
        try {
            console.log('UltrapressTutorial: Next step requested');
            if (this.currentStep === this.tutorials.length - 1) {
                this.endTutorial();
            } else {
                this.showStep(this.currentStep + 1);
            }
        } catch (error) {
            console.error('UltrapressTutorial: Error going to next step:', error);
        }
    }

    prevStep() {
        try {
            console.log('UltrapressTutorial: Previous step requested');
            if (this.currentStep > 0) {
                this.showStep(this.currentStep - 1);
            }
        } catch (error) {
            console.error('UltrapressTutorial: Error going to previous step:', error);
        }
    }

    endTutorial() {
        try {
            console.log('UltrapressTutorial: Ending tutorial');
            localStorage.setItem('ultrapressTutorialCompleted', 'true');
            this.popup.remove();
            document.querySelectorAll('.highlight-element').forEach(el => {
                el.classList.remove('highlight-element');
            });
        } catch (error) {
            console.error('UltrapressTutorial: Error ending tutorial:', error);
        }
    }
}

document.addEventListener('DOMContentLoaded', () => {
    console.log('DOM Content Loaded - Initializing Tutorial');
    window.tutorialInstance = new UltrapressTutorial();
});
