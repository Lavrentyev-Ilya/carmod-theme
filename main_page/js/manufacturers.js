document.addEventListener('DOMContentLoaded', () => {

    const searchInput = document.getElementById('CmLoSrcId');
    const resetSvg = document.querySelector('.CmLoConIn .CmLoSvgRem'); 
    const searchSvg = document.querySelector('.CmLoConIn svg:not(.CmLoSvgRem)'); 
    const vehicleButtons = document.querySelectorAll('.CmLoBtn');
    const logoContainers = document.querySelectorAll('.CmLogoCont');
    const errorMessage = document.querySelector('.CmSrcErr');
	

    function toggleSvgIcons(value) {
        if (!resetSvg || !searchSvg) return;
        resetSvg.style.opacity = value.length > 0 ? '1' : '0';
        resetSvg.style.pointerEvents = value.length > 0 ? 'auto' : 'none';
        searchSvg.style.opacity = value.length > 0 ? '0' : '1';
        searchSvg.style.pointerEvents = value.length > 0 ? 'none' : 'auto';
    }

    function resetSearch() {
        if (searchInput) {
            searchInput.value = '';
            toggleSvgIcons('');
            filterBrands('');
        }
    }

    function getActiveCategory() {
        const activeButton = document.querySelector('.CmLoBtn.active');
        return activeButton ? activeButton.dataset.type : 'passenger';
    }

    function resetAllCategories() {
        logoContainers.forEach(container => {
            container.querySelectorAll('.CmBrLo').forEach(logo => {
                logo.style.display = 'block'; 
            });
        });

        document.querySelectorAll('.CmScBrTit').forEach(section => {
            section.querySelectorAll('.CmSecBrand').forEach(brand => {
                brand.style.display = 'flex';
                if (brand.classList.contains('hidden')) {
                    brand.style.display = 'none'; 
                }
            });
            const brandContainer = section.querySelector('.CmSecBrandCont');
            const btnMore = section.querySelector('.CmSecBtn');
            if (brandContainer && btnMore) {
                brandContainer.classList.remove('expanded');
                const btnTxt = btnMore.querySelector('.CmSecBtnTxt');
                const btnTxtMor = btnMore.querySelector('.CmSecBtnTxtMor');
                const btnSvg = btnMore.querySelector('#CmBtnMoreSvg');
                if (btnTxt) btnTxt.style.display = 'inline';
                if (btnTxtMor) btnTxtMor.style.display = 'none';
                if (btnSvg) btnSvg.style.transform = 'rotate(0deg)';
                btnMore.style.display = 'block';
            }
        });

        if (errorMessage) {
            errorMessage.classList.remove('visible');
        }
    }

    function filterBrands(query) {
        const searchQuery = query.trim().toLowerCase(); 
        const activeCategory = getActiveCategory();

        logoContainers.forEach(c => c.style.display = 'none');
        document.querySelectorAll('.CmScBrTit').forEach(s => s.style.display = 'none');

        const logoContainer = document.querySelector(`.CmLogoCont[data-type="${activeCategory}"]`);
        const brandSection = document.querySelector(`.CmScBrTit[data-type="${activeCategory}"]`);

        let hasMatches = false;

        if (logoContainer) {
            logoContainer.style.display = 'grid';
            logoContainer.querySelectorAll('.CmBrLo').forEach(logo => {
                const brand = logo.dataset.brand.toLowerCase();
                const isMatch = searchQuery.length > 0 && brand.startsWith(searchQuery);
                logo.style.display = isMatch || searchQuery.length === 0 ? 'block' : 'none';
                if (isMatch) hasMatches = true;
            });
        }

        if (brandSection) {
            brandSection.style.display = 'block';
            brandSection.querySelectorAll('.CmSecBrand').forEach(brand => {
                const brandText = brand.textContent.trim().toLowerCase();
                const isMatch = searchQuery.length > 0 && brandText.startsWith(searchQuery);
                if (isMatch || searchQuery.length === 0) {
                    brand.style.display = 'flex';
                    if (brand.classList.contains('hidden') && !searchQuery) {
                        brand.style.display = 'none';
                    }
                    if (isMatch) hasMatches = true;
                } else {
                    brand.style.display = 'none';
                }
            });

            const brandContainer = brandSection.querySelector('.CmSecBrandCont');
            const btnMore = brandSection.querySelector('.CmSecBtn');
            if (brandContainer && btnMore) {
                btnMore.style.display = searchQuery.length > 0 ? 'none' : 'flex';
                if (searchQuery.length > 0) {
                    brandContainer.classList.remove('expanded');
                    const btnTxt = btnMore.querySelector('.CmSecBtnTxt');
                    const btnTxtMor = btnMore.querySelector('.CmSecBtnTxtMor');
                    const btnSvg = btnMore.querySelector('#CmBtnMoreSvg');
                    if (btnTxt) btnTxt.style.display = 'inline';
                    if (btnTxtMor) btnTxtMor.style.display = 'none';
                    if (btnSvg) btnSvg.style.transform = 'rotate(0deg)';
                }
            }
        }

        if (errorMessage) {
            if (searchQuery.length > 0 && !hasMatches) {
                errorMessage.classList.add('visible');
            } else {
                errorMessage.classList.remove('visible');
            }
        }
    }

	if (searchInput && !searchInput.dataset.listenerAdded) {
		searchInput.addEventListener('keydown', (e) => {
			if (e.key === ' ') {
				e.preventDefault();
			}
		});
		searchInput.addEventListener('input', (e) => {
			let value = e.target.value.replace(/[^\p{L}]/gu, '');
			e.target.value = value;
			toggleSvgIcons(value);
			
			document.querySelectorAll('.CmSecBrand').forEach((el) => {
			  el.classList.remove('hidden');
			});

			filterBrands(value);
		});
		searchInput.dataset.listenerAdded = 'true';
	}

    if (resetSvg && !resetSvg.dataset.listenerAdded) {
        resetSvg.addEventListener('click', resetSearch);
        resetSvg.dataset.listenerAdded = 'true';
    }

    vehicleButtons.forEach(button => {
		if (!button.dataset.listenerAdded) {
			button.addEventListener('click', (e) => {
				e.preventDefault();
				if (button.classList.contains('active')) return;

				vehicleButtons.forEach(btn => btn.classList.remove('active'));
				button.classList.add('active');

				const selnameValue = button.getAttribute('selname');
				const cmHeadManufElement = document.querySelector('.CmHeadManuf');
				if (cmHeadManufElement) {
					cmHeadManufElement.textContent = selnameValue;
				}

				resetAllCategories();
				resetSearch();
			});
			button.dataset.listenerAdded = 'true';
		}
	});
	
    function handleShowMoreClick(e) {
        if (e.target.closest('.CmSecBtn')) {
            e.preventDefault();
            const btnContainer = e.target.closest('.CmSecBtn');
            const brandTitle = btnContainer.closest('.CmScBrTit');
			if (!brandTitle) return;
            const brandContainer = brandTitle.querySelector('.CmSecBrandCont');
			if (!brandContainer) return;
            const btnTxt = btnContainer.querySelector('.CmSecBtnTxt');
            const btnTxtMor = btnContainer.querySelector('.CmSecBtnTxtMor');
            const btnSvg = btnContainer.querySelector('#CmBtnMoreSvg');
            const isExpanded = brandContainer.classList.contains('expanded');
            const maxVisible = 21;

            if (!isExpanded) {
                const hiddenBrands = brandContainer.querySelectorAll('.CmSecBrand.hidden');
                hiddenBrands.forEach((brand, index) => {
                    setTimeout(() => {
                        brand.classList.remove('hidden');
                        brand.style.display = 'flex';
                    }, index * 0);
                });
                brandContainer.classList.add('expanded');
            } else {
                const allBrands = brandContainer.querySelectorAll('.CmSecBrand');
                allBrands.forEach((brand, index) => {
                    if (index >= maxVisible) {
                        brand.classList.add('hidden');
                        brand.style.display = 'none';
                    }
                });
                brandContainer.classList.remove('expanded');
                setTimeout(() => {
                    brandContainer.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }, 200);
            }

            if (btnTxt && btnTxtMor) {
                btnTxt.style.display = isExpanded ? 'inline' : 'none';
                btnTxtMor.style.display = isExpanded ? 'none' : 'inline';
            }
            if (btnSvg) {
                btnSvg.style.transform = isExpanded ? 'rotate(0deg)' : 'rotate(180deg)';
            }
        }
    }
    document.addEventListener('click', handleShowMoreClick);
});