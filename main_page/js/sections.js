document.addEventListener("DOMContentLoaded", () => {

	const CmGrid = document.querySelector(".CmSctGrid");
	if (!CmGrid) return;

	const isTouchNavigation = () => {
		return window.matchMedia('(hover: none), (pointer: coarse)').matches || window.innerWidth <= 768;
	};
	const closeTouchSections = (exceptSection = null) => {
		CmGrid.querySelectorAll('.CmSctTouchOpen').forEach((section) => {
			if (section !== exceptSection) {
				section.classList.remove('CmSctTouchOpen');
			}
		});
	};
	let lastPointerType = '';

	CmGrid.addEventListener('pointerdown', (event) => {
		lastPointerType = event.pointerType || '';
	}, { passive: true });

	CmGrid.addEventListener('click', (event) => {
		const section = event.target.closest('.CmSctFx');
		if (!section || !CmGrid.contains(section)) return;

		const submenu = section.querySelector('.CmSctSubs');
		if (!submenu) return;

		if (event.target.closest('.CmSctSubs a')) {
			return;
		}

		const touchLike = lastPointerType === 'touch' || lastPointerType === 'pen' || isTouchNavigation();
		if (!touchLike) return;

		event.preventDefault();
		event.stopPropagation();
		closeTouchSections(section);
		section.classList.add('CmSctTouchOpen');
	}, true);

	document.addEventListener('click', (event) => {
		if (!event.target.closest('.CmSctFx')) {
			closeTouchSections();
		}
	});

	document.addEventListener('keydown', (event) => {
		if (event.key === 'Escape') {
			closeTouchSections();
		}
	});

	window.addEventListener('resize', () => {
		if (!isTouchNavigation()) {
			closeTouchSections();
		}
	});

	// OPEN/HIDE MORE CATEGORY
	const CmBtnMore = document.getElementById("CmBtnMore");
	const CmBtnSvg = document.getElementById("CmBtnMoreSvg");
	const CmSecBtn = CmBtnMore ? CmBtnMore.parentElement : null;
	let isExpanded = false;
	const CmEmptyStyle = { display: '' };
	const CmEmptyClassList = { remove(){}, toggle(){} };
	const CmBtnTxt = (CmSecBtn ? CmSecBtn.querySelector(".CmSecBtnTxt") : null) || { style: CmEmptyStyle };
	const CmBtnTxtMor = (CmSecBtn ? CmSecBtn.querySelector(".CmSecBtnTxtMor") : null) || { style: CmEmptyStyle };
	const CmBtnSvgSafe = CmBtnSvg || { classList: CmEmptyClassList };
	CmBtnTxt.style.display = "inline";
	CmBtnTxtMor.style.display = "none";
	if (CmSecBtn) CmSecBtn.addEventListener("click", (event) => {
		event.preventDefault();
		const toggleSections = CmGrid.querySelectorAll(".CmSctFx:not(.CmSctFixed)");
		isExpanded = !isExpanded;
		closeTouchSections();
		if (isExpanded) {
			toggleSections.forEach((section, index) => {
				const delay = index * 60;
				section.style.transitionDelay = `${delay}ms`;
				setTimeout(() => {
					section.classList.remove("CmSctHidden");
				}, delay);
			});
		} else {
			toggleSections.forEach(section => {
				section.style.transitionDelay = '0ms';
				section.classList.add("CmSctHidden");
			});
			setTimeout(() => {
				const rect = CmGrid.getBoundingClientRect();
				const scrollTop = window.scrollY || document.documentElement.scrollTop;
				const offset = rect.top + scrollTop - window.innerHeight / 2 + rect.height / 2;
				window.scrollTo({ top: offset, behavior: "smooth" });
			}, 200);
		}
		CmBtnTxt.style.display = isExpanded ? "none" : "inline";
		CmBtnTxtMor.style.display = isExpanded ? "inline" : "none";
		CmBtnSvgSafe.classList.toggle("up", isExpanded);
	});

	 // SEARCH IN CATALOG
		(function () {
		const searchInput = document.getElementById('CmPtsSrcId');
		const grid = document.querySelector('.CmSctGrid');
		const noResultsMessage = grid.querySelector('.CmNoResMess');
		const allSections = Array.from(grid.querySelectorAll('.CmSctFx'));
		const initialSections = allSections.slice();
		const initialState = initialSections.map(el => el.classList.contains('CmSctFixed'));
		const originalSubsMap = new Map();
		
		allSections.forEach(section => {
			const subs = section.querySelector('.CmSctSubs');
			if (subs) {
				const links = subs.querySelectorAll('.CmSctSa');
				originalSubsMap.set(section, Array.from(links));
			}
		});

		let searchTimeout;
		
		searchInput.addEventListener('input', (e) => {
			const value = e.target.value;
			const filteredValue = value.replace(/[^\p{L} ]/gu, '');
			if (value !== filteredValue) {
				e.target.value = filteredValue;
				searchInput.dispatchEvent(new Event('input', { bubbles: true }));
			}
		});

		searchInput.addEventListener('input', () => {
			clearTimeout(searchTimeout);
			
			const raw = searchInput.value.trim();
			const term = raw.toLowerCase();
			
			let isExpanded = false;
			CmBtnTxt.style.display = 'inline';
			CmBtnTxtMor.style.display = 'none';
			CmBtnSvgSafe.classList.remove('up');
			closeTouchSections();
			
			if (noResultsMessage) {
				noResultsMessage.classList.remove('visible');
			}
			
			document.querySelectorAll('.CmSubProdTyp').forEach(el => {
				el.innerHTML = '';
			});
			
			if (term.length < 2) {
				initialSections.forEach((el, idx) => {
					grid.appendChild(el);
					el.classList.remove('search-match');
					const subs = el.querySelector('.CmSctSubs');
					const titleLink = el.querySelector('.CmSctMa');
					const name = el.querySelector('.CmSctName');
					
					if (subs && originalSubsMap.has(el)) {
						const links = subs.querySelectorAll('.CmSctSa');
						links.forEach(link => link.remove());
						originalSubsMap.get(el).forEach(link => subs.appendChild(link));
					}
					
					if (initialState[idx]) {
						el.classList.replace('CmSctHidden', 'CmSctFixed');
					} else {
						el.classList.replace('CmSctFixed', 'CmSctHidden');
					}
					
					if (name) {
						name.style.opacity = '1';
						name.style.visibility = 'visible';
					}
					if (titleLink) titleLink.style.display = 'flex';
				});
				return;
			}
			
			searchTimeout = setTimeout(() => {
				fetch(window.location.href, {
					method: 'POST',
					headers: {
						'Content-Type': 'application/x-www-form-urlencoded',
					},
					body: 'CmSrchScts=' + encodeURIComponent(term)
				})
				.then(response => response.json())
				.then(data => {
					allSections.forEach(el => {
						const sectionId = el.querySelector('.CmSubProdTyp')?.dataset.sectionId;
						const subs = el.querySelector('.CmSctSubs');
						const prodContainer = el.querySelector('.CmSubProdTyp');
						
						if (data.pt && data.pt[sectionId]) {
							el.classList.add('search-match');
							el.classList.replace('CmSctHidden', 'CmSctFixed');
							
							if (subs) {
								const links = subs.querySelectorAll('.CmSctSa');
								links.forEach(link => link.remove());
								
								const searchTerm = term.toLowerCase();
								const matchingLinks = originalSubsMap.get(el).filter(a => 
									a.dataset.name && a.dataset.name.toLowerCase().includes(searchTerm)
								);
								
								matchingLinks.forEach(link => subs.appendChild(link));
							}
							
							if (prodContainer) {
								prodContainer.innerHTML = Object.values(data.pt[sectionId])
									.map(item => item.PtName)
									.join(', ');
							}
						} else {
							el.classList.remove('search-match');
							el.classList.replace('CmSctFixed', 'CmSctHidden');
							
							if (prodContainer) {
								prodContainer.innerHTML = '';
							}
						}
					});
					
					if (noResultsMessage) {
						if (!data.pt || Object.keys(data.pt).length === 0) {
							noResultsMessage.classList.add('visible');
						} else {
							noResultsMessage.classList.remove('visible');
						}
					}
				})
				.catch(error => console.error('Search error:', error));
			}, 300);
		});
	})();

	/*
	(function () {
		const searchInput = document.getElementById('CmPtsSrcId');
		const grid = document.querySelector('.CmSctGrid');
		const noResultsMessage = grid.querySelector('.CmNoResMess');
		const allSections = Array.from(grid.querySelectorAll('.CmSctFx'));
		const initialSections = allSections.slice();
		const initialState = initialSections.map(el =>
			el.classList.contains('CmSctFixed')
		);

		const originalSubsMap = new Map();
		allSections.forEach(section => {
			const subs = section.querySelector('.CmSctSubs');
			if (subs) {
				const links = subs.querySelectorAll('.CmSctSa');
				originalSubsMap.set(section, Array.from(links));
			}
		});

		searchInput.addEventListener('input', (e) => {
			const value = e.target.value;
			const filteredValue = value.replace(/[^\p{L} ]/gu, '');
			if (value !== filteredValue) {
				e.target.value = filteredValue;
				searchInput.dispatchEvent(new Event('input', { bubbles: true }));
			}
		});

		searchInput.addEventListener('input', () => {
			const raw = searchInput.value.trim();
			const term = raw.toLowerCase();

			let isExpanded = false;
			CmBtnTxt.style.display = 'inline';
			CmBtnTxtMor.style.display = 'none';
			CmBtnSvg.classList.remove('up');

			if (noResultsMessage) {
				noResultsMessage.classList.remove('visible');
			}

			if (term.length < 2) {
				initialSections.forEach((el, idx) => {
					grid.appendChild(el);
					el.classList.remove('search-match');

					const subs = el.querySelector('.CmSctSubs');
					const titleLink = el.querySelector('.CmSctMa');
					const name = el.querySelector('.CmSctName');
					if (subs && originalSubsMap.has(el)) {
						const links = subs.querySelectorAll('.CmSctSa');
						links.forEach(link => link.remove());
						originalSubsMap.get(el).forEach(link => subs.appendChild(link));
					}

					if (initialState[idx]) {
						el.classList.replace('CmSctHidden', 'CmSctFixed');
					} else {
						el.classList.replace('CmSctFixed', 'CmSctHidden');
					}

					if (name) {
						name.style.opacity = '1';
						name.style.visibility = 'visible';
					}

					if (titleLink) titleLink.style.display = 'flex';
				});
				return;
			}

			const matches = allSections.filter(el => {
				const titleLink = el.querySelector('.CmSctMa');
				const hasTitleMatch = titleLink && 
									(titleLink.textContent.toLowerCase().includes(term) || 
									 titleLink.href.toLowerCase().includes(term));
				
				const subs = el.querySelector('.CmSctSubs');
				let hasSubsMatch = false;
				if (subs) {
					const links = Array.from(subs.querySelectorAll('.CmSctSa'));
					hasSubsMatch = links.some(link => 
						link.dataset.name && link.dataset.name.toLowerCase().includes(term)
					);
				}

				return hasTitleMatch || hasSubsMatch;
			}).sort((a, b) => {
				const aTitle = a.querySelector('.CmSctMa')?.textContent.toLowerCase() || '';
				const bTitle = b.querySelector('.CmSctMa')?.textContent.toLowerCase() || '';
				const aTitleMatch = aTitle.includes(term) ? 0 : 1;
				const bTitleMatch = bTitle.includes(term) ? 0 : 1;
				return aTitleMatch - bTitleMatch || aTitle.localeCompare(bTitle);
			});

			const nonMatches = allSections.filter(el => !matches.includes(el));

			if (noResultsMessage && matches.length === 0) {
				noResultsMessage.classList.add('visible');
			}

			matches.concat(nonMatches).forEach(el => {
				grid.appendChild(el);

				const subs = el.querySelector('.CmSctSubs');
				const titleLink = el.querySelector('.CmSctMa');
				const name = el.querySelector('.CmSctName');

				if (matches.includes(el)) {
					el.classList.add('search-match');
					el.classList.replace('CmSctHidden', 'CmSctFixed');

					if (subs && originalSubsMap.has(el)) {
						const links = subs.querySelectorAll('.CmSctSa');
						links.forEach(link => link.remove());
						const filteredLinks = originalSubsMap.get(el).filter(a =>
							a.dataset.name && a.dataset.name.toLowerCase().includes(term)
						);
						filteredLinks.forEach(link => subs.appendChild(link));
					}

					if (name) {
						name.style.opacity = '0';
						name.style.visibility = 'hidden';
					}
				} else {
					el.classList.remove('search-match');
					el.classList.replace('CmSctFixed', 'CmSctHidden');

					if (subs && originalSubsMap.has(el)) {
						const links = subs.querySelectorAll('.CmSctSa');
						links.forEach(link => link.remove());
						originalSubsMap.get(el).forEach(link => subs.appendChild(link));
					}

					if (name) {
						name.style.opacity = '1';
						name.style.visibility = 'visible';
					}
				}

				if (titleLink && el.classList.contains('CmSctFixed')) {
					titleLink.style.display = 'flex';
				}
			});
		});
	})(); */

	// RESET BUTTON AND MORE BUTTON VISIBILITY
	(function () {
		const searchInput = document.getElementById('CmPtsSrcId');
		const svgs = document.querySelectorAll('.CmPtsInpGr svg');
		const searchSvg = svgs[0]; // FindCategoryLoupe
		const resetSvg = svgs[1]; // PartsReset
		const moreButtonContainer = document.querySelector('.CmSecBtn');

		if (!searchInput) console.error('searchInput (#CmPtsSrcId) not found');
		if (!searchSvg) console.error('searchSvg (first SVG in .CmPtsInpGr) not found');
		if (!resetSvg) console.error('resetSvg (second SVG in .CmPtsInpGr) not found');
		if (!searchInput || !resetSvg || !searchSvg) return;

		function toggleSvgIcons(value) {
			resetSvg.style.opacity = value.length > 0 ? '1' : '0';
			resetSvg.style.pointerEvents = value.length > 0 ? 'auto' : 'none';
			searchSvg.style.opacity = value.length > 0 ? '0' : '1';
			searchSvg.style.pointerEvents = value.length > 0 ? 'none' : 'auto';
			if (moreButtonContainer) {
				moreButtonContainer.style.opacity = value.length > 0 ? '0' : '1';
				moreButtonContainer.style.pointerEvents = value.length > 0 ? 'none' : 'auto';
			}
			closeTouchSections();
		}

		searchInput.addEventListener('input', () => {
			const value = searchInput.value.trim();
			toggleSvgIcons(value);
		});

		resetSvg.addEventListener('click', () => {
			searchInput.value = '';
			toggleSvgIcons('');
			searchInput.dispatchEvent(new Event('input'));
		});
	})();
});
