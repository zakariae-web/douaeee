document.addEventListener('DOMContentLoaded', () => {
    const countContainer = document.querySelector('.count');
    const prevBtn = document.querySelector('.prevBtn');
    const nextBtn = document.querySelector('.nextBtn');
    const activeBtn = document.querySelector('.activeBtn');
    const leftContainer = document.querySelector('.leftContainer');
    const rightContainer = document.querySelector('.rightContainer');
    const btns = document.querySelector('.containerBtns');

    const changeActiveBtn = (value) => {
        activeBtn.textContent = value;
        activeBtn.disabled = true;
        document.getElementById("current-page").textContent = value;
    }

    const init = () => {
        changeActiveBtn(currentPage);
        updateRightContainer(currentPage);
        updateBlockBtn(currentPage);
        updateLeftContainer(currentPage);
    }

    const handlePrevBtn = () => {
        const curActive = Number(activeBtn.textContent) - 1;
        if (curActive >= 1) window.location.href = `?page=${curActive}`;
    }

    const handleNextBtn = () => {
        const curActive = Number(activeBtn.textContent) + 1;
        if (curActive <= countPage) window.location.href = `?page=${curActive}`;
    }

    const updatePaginate = (value) => {
        window.location.href = `?page=${value}`;
    }

    const updateBlockBtn = (value) => {
        prevBtn.disabled = value <= 1;
        nextBtn.disabled = value >= countPage;
    }

    const createButton = (text, disabled = false) => {
        const button = document.createElement('button');
        button.textContent = text;
        button.classList.add('numberBtn');
        if (disabled) button.disabled = true;
        return button;
    };

    const updateContainer = (container, array, disabledIndexes = []) => {
        container.innerHTML = '';
        for (let i = 0; i < array.length; i++) {
            const val = array[i];
            const isDisabled = disabledIndexes.includes(i);
            const button = createButton(val, isDisabled);
            if (!isDisabled) {
                button.addEventListener('click', () => updatePaginate(val));
            }
            container.appendChild(button);
        }
    };

    const updateLeftContainer = (value) => {
        if (value <= 4) {
            updateContainer(leftContainer, Array.from({length: value - 1}, (_, i) => i + 1));
        } else {
            updateContainer(leftContainer, [1, '...', value - 2, value - 1], [1]);
        }
    }

    const updateRightContainer = (value) => {
        const tail = countPage - value;
        if (tail <= 3) {
            updateContainer(rightContainer, Array.from({length: tail}, (_, i) => value + i + 1));
        } else {
            updateContainer(rightContainer, [value + 1, value + 2, '...', countPage], [2]);
        }
    }

    prevBtn.addEventListener('click', handlePrevBtn);
    nextBtn.addEventListener('click', handleNextBtn);

    init();
});
