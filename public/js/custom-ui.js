/**
 * Unified Custom UI System - OSIS SMKN 1 Tarumajaya
 * Custom Select Dropdowns & Custom Indonesian Datepickers
 * Consistent with Absensi Event Styling (rounded-2xl/3xl, shadow-2xl, rose accents)
 */

(function () {
    "use strict";

    const INDO_MONTHS = [
        "Januari", "Februari", "Maret", "April", "Mei", "Juni",
        "Juli", "Agustus", "September", "Oktober", "November", "Desember"
    ];

    const INDO_DAYS = ["Min", "Sen", "Sel", "Rab", "Kam", "Jum", "Sab"];

    // Elevate all ancestor containers so dropdown/calendar popups are NEVER covered by sibling elements
    function elevateAncestors(container) {
        if (!container) return;
        container.style.position = "relative";
        container.style.zIndex = "1000";

        let el = container.parentElement;
        while (el && el !== document.body) {
            const comp = window.getComputedStyle(el);
            if (comp.position === "static") {
                el.style.position = "relative";
                el.dataset.customUiSetRelative = "true";
            }
            if (!el.dataset.customUiOrigZIndex) {
                el.dataset.customUiOrigZIndex = el.style.zIndex || "empty";
            }
            el.style.zIndex = "990";
            el.dataset.elevatedByCustomUi = "true";

            // Stop at modal content, main card, or body
            if (el.tagName === "FORM" || el.classList.contains("liquid-glass-card") || el.classList.contains("app-card") || el.classList.contains("rounded-3xl") || el.id?.includes("modal")) {
                break;
            }
            el = el.parentElement;
        }
    }

    function resetAncestors(container) {
        if (!container) return;
        container.style.zIndex = "";

        let el = container.parentElement;
        while (el && el !== document.body) {
            if (el.dataset.elevatedByCustomUi === "true") {
                if (el.dataset.customUiSetRelative === "true") {
                    el.style.position = "";
                    delete el.dataset.customUiSetRelative;
                }
                if (el.dataset.customUiOrigZIndex && el.dataset.customUiOrigZIndex !== "empty") {
                    el.style.zIndex = el.dataset.customUiOrigZIndex;
                } else {
                    el.style.zIndex = "";
                }
                delete el.dataset.customUiOrigZIndex;
                delete el.dataset.elevatedByCustomUi;
            }
            if (el.tagName === "FORM" || el.classList.contains("liquid-glass-card") || el.classList.contains("app-card") || el.classList.contains("rounded-3xl") || el.id?.includes("modal")) {
                break;
            }
            el = el.parentElement;
        }
    }

    // Utility: Close all open custom popups
    function closeAllCustomPopups(exceptElement) {
        document.querySelectorAll(".custom-select-menu:not(.hidden)").forEach(menu => {
            if (!exceptElement || !menu.contains(exceptElement)) {
                menu.classList.add("hidden");
                const chevron = menu.parentElement?.querySelector(".custom-select-chevron");
                if (chevron) chevron.classList.remove("rotate-180");
                const c = menu.closest(".custom-select-container");
                if (c) {
                    resetAncestors(c);
                    const triggerBtn = c.querySelector(".custom-select-trigger");
                    if (triggerBtn) {
                        triggerBtn.style.borderColor = "#e2e8f0";
                        triggerBtn.style.boxShadow = "0 2px 6px rgba(0,0,0,0.03)";
                        triggerBtn.style.backgroundColor = "rgba(255, 255, 255, 0.9)";
                    }
                }
            }
        });

        document.querySelectorAll(".custom-datepicker-popup:not(.hidden)").forEach(popup => {
            if (!exceptElement || !popup.contains(exceptElement)) {
                popup.classList.add("hidden");
                const c = popup.closest(".custom-datepicker-container");
                if (c) {
                    resetAncestors(c);
                    const disp = c.querySelector(".custom-datepicker-display");
                    if (disp) {
                        disp.style.borderColor = "#e2e8f0";
                        disp.style.boxShadow = "0 2px 6px rgba(0,0,0,0.03)";
                        disp.style.backgroundColor = "rgba(255, 255, 255, 0.9)";
                    }
                }
            }
        });
    }

    // Global click listener to close popups when clicking outside
    document.addEventListener("click", function (e) {
        if (!e.target.closest(".custom-select-container") && !e.target.closest(".custom-datepicker-container")) {
            closeAllCustomPopups();
        }
    });

    // =========================================================================
    // 1. CUSTOM SELECT DROPDOWN
    // =========================================================================
    function initCustomSelect(selectEl) {
        if (!selectEl || selectEl.dataset.customUiInit === "true") return;
        if (selectEl.classList.contains("no-custom-select")) return;

        selectEl.dataset.customUiInit = "true";
        selectEl.style.display = "none";

        // Hide any existing static sibling chevron icon in parent wrapper
        const parent = selectEl.parentElement;
        if (parent) {
            const siblingChevron = parent.querySelector(".pointer-events-none");
            if (siblingChevron && siblingChevron.querySelector("svg")) {
                siblingChevron.style.display = "none";
            }
        }

        // Create container wrapper
        const container = document.createElement("div");
        container.className = "custom-select-container relative w-full";
        if (selectEl.classList.contains("hidden")) {
            container.classList.add("hidden");
        }

        // Determine initial label and value
        let selectedOption = selectEl.options[selectEl.selectedIndex] || selectEl.options[0];
        let labelText = selectedOption ? selectedOption.textContent.trim() : "Pilih opsi...";
        let initialVal = selectedOption ? selectedOption.value : "";

        // Trigger Button (matches liquid glass style with crisp visible border)
        const trigger = document.createElement("button");
        trigger.type = "button";
        trigger.className = "custom-select-trigger w-full flex items-center justify-between rounded-2xl px-4 py-3 text-slate-800 text-sm font-semibold outline-none transition cursor-pointer text-left select-none";
        trigger.style.cssText = "min-height: 46px; border: 1.5px solid #e2e8f0 !important; background: rgba(255, 255, 255, 0.92) !important; border-radius: 1rem !important; box-shadow: 0 2px 6px rgba(0,0,0,0.03) !important;";
        
        if (selectEl.className.includes("text-xs") || selectEl.className.includes("py-1") || selectEl.className.includes("py-1.5")) {
            trigger.className = trigger.className.replace("px-4 py-3 text-sm", "px-3 py-1.5 text-xs");
            trigger.style.minHeight = "36px";
        }

        if (selectEl.disabled) {
            trigger.disabled = true;
            trigger.classList.add("opacity-60", "bg-slate-100", "cursor-not-allowed");
        }

        // Hover effects
        trigger.addEventListener("mouseenter", function () {
            if (!trigger.disabled && menu.classList.contains("hidden")) {
                trigger.style.borderColor = "#cbd5e1";
                trigger.style.backgroundColor = "#ffffff";
            }
        });
        trigger.addEventListener("mouseleave", function () {
            if (!trigger.disabled && menu.classList.contains("hidden")) {
                trigger.style.borderColor = "#e2e8f0";
                trigger.style.backgroundColor = "rgba(255, 255, 255, 0.92)";
            }
        });

        trigger.innerHTML = `
            <span class="custom-select-label truncate flex-1">${labelText}</span>
            <svg class="custom-select-chevron h-4 w-4 text-slate-400 transition-transform duration-200 shrink-0 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
            </svg>
        `;

        // Menu Dropdown Panel
        const menu = document.createElement("div");
        menu.className = "custom-select-menu hidden absolute left-0 top-full mt-2 w-full min-w-[200px] max-h-60 overflow-y-auto bg-white/95 backdrop-blur-2xl rounded-2xl shadow-2xl border border-slate-100/80 p-2 z-[9999] custom-time-scroll";
        menu.style.boxShadow = "0 25px 60px -15px rgba(0, 0, 0, 0.15), 0 0 0 1px rgba(0, 0, 0, 0.05)";

        function buildOptions() {
            menu.innerHTML = "";
            const currentVal = selectEl.value;

            Array.from(selectEl.options).forEach((opt) => {
                if (opt.disabled && !opt.value) {
                    const header = document.createElement("div");
                    header.className = "px-3.5 py-1.5 text-[11px] font-bold uppercase tracking-wider text-slate-400";
                    header.textContent = opt.textContent;
                    menu.appendChild(header);
                    return;
                }

                const item = document.createElement("button");
                item.type = "button";
                item.dataset.value = opt.value;

                const isSelected = opt.value === currentVal || (!currentVal && opt.selected);
                let itemClass = "custom-select-option w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-bold transition text-left ";
                if (isSelected) {
                    itemClass += "bg-rose-50/90 text-rose-600 font-extrabold";
                } else {
                    itemClass += "text-slate-700 hover:bg-rose-50 hover:text-rose-600";
                }
                item.className = itemClass;

                item.innerHTML = `
                    <span class="truncate">${opt.textContent.trim()}</span>
                    <span class="check-icon text-rose-600 text-sm font-bold ${isSelected ? "" : "hidden"}">✓</span>
                `;

                item.addEventListener("click", function (e) {
                    e.stopPropagation();
                    selectEl.value = opt.value;

                    // Update trigger label
                    trigger.querySelector(".custom-select-label").textContent = opt.textContent.trim();

                    // Update active state in menu
                    menu.querySelectorAll(".custom-select-option").forEach((btn) => {
                        btn.className = "custom-select-option w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-bold transition text-left text-slate-700 hover:bg-rose-50 hover:text-rose-600";
                        const check = btn.querySelector(".check-icon");
                        if (check) check.classList.add("hidden");
                    });
                    item.className = "custom-select-option w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-bold transition text-left bg-rose-50/90 text-rose-600 font-extrabold";
                    const activeCheck = item.querySelector(".check-icon");
                    if (activeCheck) activeCheck.classList.remove("hidden");

                    // Close menu
                    menu.classList.add("hidden");
                    const chevron = trigger.querySelector(".custom-select-chevron");
                    if (chevron) chevron.classList.remove("rotate-180");

                    // Dispatch change event to trigger listeners & inline onchange
                    selectEl.dispatchEvent(new Event("change", { bubbles: true }));
                    if (typeof selectEl.onchange === "function") {
                        selectEl.onchange();
                    }
                });

                menu.appendChild(item);
            });
        }

        buildOptions();

        // Toggle dropdown on trigger click
        trigger.addEventListener("click", function (e) {
            e.stopPropagation();
            if (selectEl.disabled) return;
            const isHidden = menu.classList.contains("hidden");
            closeAllCustomPopups(menu);

            if (isHidden) {
                buildOptions();
                menu.classList.remove("hidden");
                elevateAncestors(container);

                trigger.style.borderColor = "#e11d48";
                trigger.style.boxShadow = "0 0 0 4px rgba(225, 29, 72, 0.12)";
                trigger.style.backgroundColor = "#ffffff";

                const chevron = trigger.querySelector(".custom-select-chevron");
                if (chevron) chevron.classList.add("rotate-180");

                // Auto position: check if menu goes off screen bottom
                const rect = trigger.getBoundingClientRect();
                const spaceBelow = window.innerHeight - rect.bottom;
                const spaceAbove = rect.top;
                if (spaceBelow < 220 && spaceAbove > spaceBelow && spaceAbove > 180) {
                    menu.classList.remove("top-full", "mt-2");
                    menu.classList.add("bottom-full", "mb-2");
                } else {
                    menu.classList.remove("bottom-full", "mb-2");
                    menu.classList.add("top-full", "mt-2");
                }

                // Auto scroll to active item
                const activeItem = menu.querySelector(".bg-rose-50\\/90");
                if (activeItem) {
                    activeItem.scrollIntoView({ block: "nearest" });
                }
            } else {
                menu.classList.add("hidden");
                resetAncestors(container);

                trigger.style.borderColor = "#e2e8f0";
                trigger.style.boxShadow = "0 2px 6px rgba(0,0,0,0.03)";
                trigger.style.backgroundColor = "rgba(255, 255, 255, 0.92)";

                const chevron = trigger.querySelector(".custom-select-chevron");
                if (chevron) chevron.classList.remove("rotate-180");
            }
        });

        // Insert container into DOM right before selectEl
        selectEl.parentNode.insertBefore(container, selectEl);
        container.appendChild(trigger);
        container.appendChild(menu);
        container.appendChild(selectEl);

        // MutationObserver to watch options or attribute changes on selectEl
        const observer = new MutationObserver(function (mutations) {
            mutations.forEach(function (mutation) {
                if (mutation.type === "childList") {
                    buildOptions();
                    const activeOpt = selectEl.options[selectEl.selectedIndex];
                    if (activeOpt) {
                        trigger.querySelector(".custom-select-label").textContent = activeOpt.textContent.trim();
                    }
                } else if (mutation.type === "attributes") {
                    if (mutation.attributeName === "class") {
                        if (selectEl.classList.contains("hidden")) {
                            container.classList.add("hidden");
                        } else {
                            container.classList.remove("hidden");
                        }
                    } else if (mutation.attributeName === "disabled") {
                        if (selectEl.disabled) {
                            trigger.disabled = true;
                            trigger.classList.add("opacity-60", "bg-slate-100", "cursor-not-allowed");
                        } else {
                            trigger.disabled = false;
                            trigger.classList.remove("opacity-60", "bg-slate-100", "cursor-not-allowed");
                        }
                    }
                }
            });
        });

        observer.observe(selectEl, { childList: true, attributes: true });

        // Listen for external change event on selectEl
        selectEl.addEventListener("change", function () {
            const activeOpt = selectEl.options[selectEl.selectedIndex];
            if (activeOpt) {
                trigger.querySelector(".custom-select-label").textContent = activeOpt.textContent.trim();
            }
            buildOptions();
        });

        // Helper method on selectEl for programmatic sync
        selectEl._syncCustomSelect = function () {
            const activeOpt = selectEl.options[selectEl.selectedIndex];
            if (activeOpt) {
                trigger.querySelector(".custom-select-label").textContent = activeOpt.textContent.trim();
            }
            buildOptions();
            if (selectEl.classList.contains("hidden")) {
                container.classList.add("hidden");
            } else {
                container.classList.remove("hidden");
            }
            if (selectEl.disabled) {
                trigger.disabled = true;
                trigger.classList.add("opacity-60", "bg-slate-100", "cursor-not-allowed");
            } else {
                trigger.disabled = false;
                trigger.classList.remove("opacity-60", "bg-slate-100", "cursor-not-allowed");
            }
        };
    }

    // =========================================================================
    // 2. CUSTOM INDONESIAN DATEPICKER (ROBUST INLINE GRID)
    // =========================================================================
    function initCustomDatePicker(dateInput) {
        if (!dateInput || dateInput.dataset.customDateInit === "true") return;
        if (dateInput.classList.contains("no-custom-datepicker")) return;

        dateInput.dataset.customDateInit = "true";
        dateInput.style.display = "none";

        // Container
        const container = document.createElement("div");
        container.className = "custom-datepicker-container relative w-full";
        if (dateInput.classList.contains("hidden")) {
            container.classList.add("hidden");
        }

        // Parse initial value (YYYY-MM-DD)
        let selectedDate = null;
        if (dateInput.value && dateInput.value.includes("-")) {
            const parts = dateInput.value.split("-");
            selectedDate = new Date(parseInt(parts[0]), parseInt(parts[1]) - 1, parseInt(parts[2]));
        }

        function formatDisplay(date) {
            if (!date || isNaN(date.getTime())) return "";
            return `${date.getDate()} ${INDO_MONTHS[date.getMonth()]} ${date.getFullYear()}`;
        }

        function formatYMD(date) {
            if (!date || isNaN(date.getTime())) return "";
            const y = date.getFullYear();
            const m = String(date.getMonth() + 1).padStart(2, "0");
            const d = String(date.getDate()).padStart(2, "0");
            return `${y}-${m}-${d}`;
        }

        // Trigger input wrapper
        const triggerWrapper = document.createElement("div");
        triggerWrapper.className = "relative w-full";

        const displayInput = document.createElement("input");
        displayInput.type = "text";
        displayInput.readOnly = true;
        displayInput.placeholder = dateInput.placeholder || "Pilih Tanggal...";
        displayInput.value = formatDisplay(selectedDate);
        displayInput.className = "custom-datepicker-display w-full rounded-2xl px-4 py-3 text-slate-800 text-sm font-semibold outline-none transition cursor-pointer select-none";
        displayInput.style.cssText = "min-height: 46px; border: 1.5px solid #e2e8f0 !important; background: rgba(255, 255, 255, 0.92) !important; border-radius: 1rem !important; box-shadow: 0 2px 6px rgba(0,0,0,0.03) !important; padding-right: 44px !important;";

        displayInput.addEventListener("mouseenter", function () {
            if (!dateInput.disabled && popup.classList.contains("hidden")) {
                displayInput.style.borderColor = "#cbd5e1";
                displayInput.style.backgroundColor = "#ffffff";
            }
        });
        displayInput.addEventListener("mouseleave", function () {
            if (!dateInput.disabled && popup.classList.contains("hidden")) {
                displayInput.style.borderColor = "#e2e8f0";
                displayInput.style.backgroundColor = "rgba(255, 255, 255, 0.92)";
            }
        });

        // Calendar icon wrapper (always cleanly anchored to the right)
        const iconWrapper = document.createElement("div");
        iconWrapper.className = "pointer-events-none text-slate-400";
        iconWrapper.style.cssText = "position: absolute; right: 14px; top: 50%; transform: translateY(-50%); display: flex; align-items: center; justify-content: center; pointer-events: none;";
        iconWrapper.innerHTML = `
            <svg style="width: 20px; height: 20px; color: #94a3b8;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
        `;

        triggerWrapper.appendChild(displayInput);
        triggerWrapper.appendChild(iconWrapper);

        // Floating Calendar Popup (With explicit inline width & styling)
        const popup = document.createElement("div");
        popup.className = "custom-datepicker-popup hidden absolute left-0 top-full mt-2";
        popup.style.cssText = "width: 320px !important; min-width: 300px !important; max-width: calc(100vw - 32px) !important; background: #ffffff !important; border-radius: 24px !important; padding: 16px !important; box-shadow: 0 25px 60px -15px rgba(0, 0, 0, 0.2), 0 0 0 1px rgba(0, 0, 0, 0.05) !important; z-index: 99999 !important; border: 1px solid #f1f5f9 !important;";

        function hideDatePicker() {
            popup.classList.add("hidden");
            resetAncestors(container);
            displayInput.style.borderColor = "#e2e8f0";
            displayInput.style.boxShadow = "0 2px 6px rgba(0,0,0,0.03)";
            displayInput.style.backgroundColor = "rgba(255, 255, 255, 0.92)";
        }

        let viewYear = selectedDate ? selectedDate.getFullYear() : new Date().getFullYear();
        let viewMonth = selectedDate ? selectedDate.getMonth() : new Date().getMonth();
        let currentMode = "days";

        popup.innerHTML = `
            <!-- Header Nav -->
            <div style="display: flex; align-items: center; justify-content: space-between; padding-bottom: 12px; margin-bottom: 12px; border-bottom: 1px solid #f1f5f9;">
                <button type="button" class="cal-nav-prev" style="padding: 6px 10px; border-radius: 12px; border: none; background: transparent; cursor: pointer; color: #64748b; font-size: 14px; font-weight: 700;" title="Bulan Sebelumnya">
                    ❮
                </button>
                <div style="display: flex; align-items: center; gap: 6px;">
                    <button type="button" class="cal-nav-month" style="padding: 6px 10px; border-radius: 12px; border: none; background: #f8fafc; cursor: pointer; color: #1e293b; font-size: 12px; font-weight: 800; display: flex; align-items: center; gap: 4px;">
                        <span class="cal-lbl-month">${INDO_MONTHS[viewMonth]}</span>
                        <span style="font-size: 10px; color: #94a3b8;">▼</span>
                    </button>
                    <button type="button" class="cal-nav-year" style="padding: 6px 10px; border-radius: 12px; border: none; background: #f8fafc; cursor: pointer; color: #1e293b; font-size: 12px; font-weight: 800; display: flex; align-items: center; gap: 4px;">
                        <span class="cal-lbl-year">${viewYear}</span>
                        <span style="font-size: 10px; color: #94a3b8;">▼</span>
                    </button>
                </div>
                <button type="button" class="cal-nav-next" style="padding: 6px 10px; border-radius: 12px; border: none; background: transparent; cursor: pointer; color: #64748b; font-size: 14px; font-weight: 700;" title="Bulan Berikutnya">
                    ❯
                </button>
            </div>

            <!-- View Days -->
            <div class="cal-view-days">
                <div style="display: grid; grid-template-columns: repeat(7, 1fr); gap: 4px; text-align: center; margin-bottom: 8px;">
                    ${INDO_DAYS.map(d => `<span style="font-size: 10px; font-weight: 700; color: #94a3b8; text-transform: uppercase;">${d}</span>`).join("")}
                </div>
                <div class="cal-days-grid" style="display: grid; grid-template-columns: repeat(7, 1fr); gap: 4px; text-align: center;"></div>
            </div>

            <!-- View Months -->
            <div class="cal-view-months hidden">
                <p style="font-size: 11px; font-weight: 700; color: #94a3b8; margin-bottom: 8px; text-transform: uppercase;">Pilih Bulan:</p>
                <div class="cal-months-grid" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 8px; text-align: center;"></div>
            </div>

            <!-- View Years -->
            <div class="cal-view-years hidden">
                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 10px;">
                    <span style="font-size: 11px; font-weight: 700; color: #475569;">Tahun Bebas:</span>
                    <input type="number" class="cal-input-year" style="width: 80px; padding: 4px 8px; font-size: 12px; font-weight: 700; border: 1px solid #cbd5e1; border-radius: 8px; outline: none;" placeholder="YYYY" min="1900" max="2100" value="${viewYear}">
                    <button type="button" class="cal-btn-apply-year" style="padding: 4px 10px; background: #e11d48; color: #ffffff; font-size: 11px; font-weight: 700; border-radius: 8px; border: none; cursor: pointer;">Terapkan</button>
                </div>
                <div class="cal-years-grid custom-time-scroll" style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 6px; max-height: 180px; overflow-y: auto; text-align: center;"></div>
            </div>

            <!-- Footer Buttons -->
            <div style="display: flex; align-items: center; justify-content: space-between; padding-top: 12px; margin-top: 12px; border-top: 1px solid #f1f5f9;">
                <button type="button" class="cal-btn-today" style="font-size: 12px; font-weight: 700; color: #e11d48; border: none; background: transparent; cursor: pointer; padding: 6px 10px; border-radius: 10px;">
                    Hari Ini
                </button>
                <div style="display: flex; align-items: center; gap: 8px;">
                    ${!dateInput.required ? `
                    <button type="button" class="cal-btn-clear" style="font-size: 12px; font-weight: 700; color: #94a3b8; border: none; background: transparent; cursor: pointer; padding: 6px 10px; border-radius: 10px;">
                        Hapus
                    </button>
                    ` : ""}
                    <button type="button" class="cal-btn-close" style="padding: 6px 14px; border-radius: 12px; background: #e11d48; color: #ffffff; font-size: 12px; font-weight: 700; border: none; cursor: pointer; box-shadow: 0 4px 10px rgba(225, 29, 72, 0.25);">
                        Selesai
                    </button>
                </div>
            </div>
        `;

        const btnPrev = popup.querySelector(".cal-nav-prev");
        const btnNext = popup.querySelector(".cal-nav-next");
        const btnMonth = popup.querySelector(".cal-nav-month");
        const lblMonth = popup.querySelector(".cal-lbl-month");
        const btnYear = popup.querySelector(".cal-nav-year");
        const lblYear = popup.querySelector(".cal-lbl-year");

        const viewDays = popup.querySelector(".cal-view-days");
        const viewMonths = popup.querySelector(".cal-view-months");
        const viewYears = popup.querySelector(".cal-view-years");

        const daysGrid = popup.querySelector(".cal-days-grid");
        const monthsGrid = popup.querySelector(".cal-months-grid");
        const yearsGrid = popup.querySelector(".cal-years-grid");
        const inputYear = popup.querySelector(".cal-input-year");
        const btnApplyYear = popup.querySelector(".cal-btn-apply-year");

        const btnToday = popup.querySelector(".cal-btn-today");
        const btnClear = popup.querySelector(".cal-btn-clear");
        const btnClose = popup.querySelector(".cal-btn-close");

        function updateLabels() {
            lblMonth.textContent = INDO_MONTHS[viewMonth];
            lblYear.textContent = viewYear;
            if (inputYear) inputYear.value = viewYear;
        }

        function switchMode(mode) {
            currentMode = mode;
            if (mode === "days") {
                viewDays.classList.remove("hidden");
                viewMonths.classList.add("hidden");
                viewYears.classList.add("hidden");

                btnMonth.style.background = "#f8fafc";
                btnMonth.style.color = "#1e293b";
                btnYear.style.background = "#f8fafc";
                btnYear.style.color = "#1e293b";
                renderDays();
            } else if (mode === "months") {
                viewDays.classList.add("hidden");
                viewMonths.classList.remove("hidden");
                viewYears.classList.add("hidden");

                btnMonth.style.background = "#ffe4e6";
                btnMonth.style.color = "#e11d48";
                btnYear.style.background = "#f8fafc";
                btnYear.style.color = "#1e293b";
                renderMonths();
            } else if (mode === "years") {
                viewDays.classList.add("hidden");
                viewMonths.classList.add("hidden");
                viewYears.classList.remove("hidden");

                btnMonth.style.background = "#f8fafc";
                btnMonth.style.color = "#1e293b";
                btnYear.style.background = "#ffe4e6";
                btnYear.style.color = "#e11d48";
                renderYears();
            }
        }

        function renderDays() {
            updateLabels();
            daysGrid.innerHTML = "";

            const firstDayIndex = new Date(viewYear, viewMonth, 1).getDay();
            const daysInMonth = new Date(viewYear, viewMonth + 1, 0).getDate();
            const today = new Date();

            for (let i = 0; i < firstDayIndex; i++) {
                const blank = document.createElement("span");
                blank.style.padding = "6px 0";
                daysGrid.appendChild(blank);
            }

            for (let d = 1; d <= daysInMonth; d++) {
                const btn = document.createElement("button");
                btn.type = "button";
                btn.textContent = d;

                const isSelected = selectedDate &&
                    selectedDate.getFullYear() === viewYear &&
                    selectedDate.getMonth() === viewMonth &&
                    selectedDate.getDate() === d;

                const isToday =
                    today.getFullYear() === viewYear &&
                    today.getMonth() === viewMonth &&
                    today.getDate() === d;

                btn.style.cssText = "width: 100% !important; padding: 7px 0 !important; border-radius: 12px !important; font-size: 12px !important; font-weight: 600 !important; cursor: pointer !important; text-align: center !important; border: none !important; transition: all 0.15s ease !important;";

                if (isSelected) {
                    btn.style.backgroundColor = "#e11d48";
                    btn.style.color = "#ffffff";
                    btn.style.fontWeight = "800";
                    btn.style.boxShadow = "0 2px 6px rgba(225, 29, 72, 0.35)";
                } else if (isToday) {
                    btn.style.border = "1px solid #fda4af";
                    btn.style.color = "#e11d48";
                    btn.style.backgroundColor = "#fff1f2";
                    btn.style.fontWeight = "700";
                } else {
                    btn.style.backgroundColor = "transparent";
                    btn.style.color = "#334155";
                    btn.onmouseenter = () => btn.style.backgroundColor = "#f1f5f9";
                    btn.onmouseleave = () => btn.style.backgroundColor = "transparent";
                }

                btn.onclick = function (e) {
                    e.stopPropagation();
                    selectedDate = new Date(viewYear, viewMonth, d);
                    dateInput.value = formatYMD(selectedDate);
                    displayInput.value = formatDisplay(selectedDate);
                    dateInput.dispatchEvent(new Event("change", { bubbles: true }));
                    dateInput.dispatchEvent(new Event("input", { bubbles: true }));
                    renderDays();
                    hideDatePicker();
                };

                daysGrid.appendChild(btn);
            }
        }

        function renderMonths() {
            updateLabels();
            monthsGrid.innerHTML = "";
            const today = new Date();

            INDO_MONTHS.forEach((name, idx) => {
                const btn = document.createElement("button");
                btn.type = "button";
                btn.textContent = name;

                const isSelected = selectedDate &&
                    selectedDate.getFullYear() === viewYear &&
                    selectedDate.getMonth() === idx;

                const isCurrentMonth =
                    today.getFullYear() === viewYear &&
                    today.getMonth() === idx;

                btn.style.cssText = "width: 100% !important; padding: 10px 4px !important; border-radius: 12px !important; font-size: 12px !important; font-weight: 600 !important; cursor: pointer !important; text-align: center !important; border: none !important; transition: all 0.15s ease !important;";

                if (isSelected) {
                    btn.style.backgroundColor = "#e11d48";
                    btn.style.color = "#ffffff";
                    btn.style.fontWeight = "800";
                } else if (isCurrentMonth) {
                    btn.style.border = "1px solid #fda4af";
                    btn.style.color = "#e11d48";
                    btn.style.backgroundColor = "#fff1f2";
                } else {
                    btn.style.backgroundColor = "#f8fafc";
                    btn.style.color = "#334155";
                    btn.onmouseenter = () => btn.style.backgroundColor = "#f1f5f9";
                    btn.onmouseleave = () => btn.style.backgroundColor = "#f8fafc";
                }

                btn.onclick = function (e) {
                    e.stopPropagation();
                    viewMonth = idx;
                    switchMode("days");
                };

                monthsGrid.appendChild(btn);
            });
        }

        function renderYears() {
            updateLabels();
            yearsGrid.innerHTML = "";
            const today = new Date();

            const startYear = viewYear - 15;
            const endYear = viewYear + 16;

            for (let y = startYear; y <= endYear; y++) {
                const btn = document.createElement("button");
                btn.type = "button";
                btn.textContent = y;

                const isSelected = selectedDate && selectedDate.getFullYear() === y;
                const isCurrentYear = today.getFullYear() === y;

                btn.style.cssText = "width: 100% !important; padding: 8px 2px !important; border-radius: 10px !important; font-size: 11px !important; font-weight: 600 !important; cursor: pointer !important; text-align: center !important; border: none !important; transition: all 0.15s ease !important;";

                if (isSelected) {
                    btn.style.backgroundColor = "#e11d48";
                    btn.style.color = "#ffffff";
                    btn.style.fontWeight = "800";
                } else if (isCurrentYear) {
                    btn.style.border = "1px solid #fda4af";
                    btn.style.color = "#e11d48";
                    btn.style.backgroundColor = "#fff1f2";
                } else {
                    btn.style.backgroundColor = "#f8fafc";
                    btn.style.color = "#334155";
                    btn.onmouseenter = () => btn.style.backgroundColor = "#f1f5f9";
                    btn.onmouseleave = () => btn.style.backgroundColor = "#f8fafc";
                }

                btn.onclick = function (e) {
                    e.stopPropagation();
                    viewYear = y;
                    switchMode("days");
                };

                yearsGrid.appendChild(btn);
            }
        }

        // Navigation handlers
        btnPrev.addEventListener("click", function (e) {
            e.stopPropagation();
            if (currentMode === "days") {
                viewMonth--;
                if (viewMonth < 0) {
                    viewMonth = 11;
                    viewYear--;
                }
                renderDays();
            } else if (currentMode === "months") {
                viewYear--;
                renderMonths();
            } else if (currentMode === "years") {
                viewYear -= 20;
                renderYears();
            }
        });

        btnNext.addEventListener("click", function (e) {
            e.stopPropagation();
            if (currentMode === "days") {
                viewMonth++;
                if (viewMonth > 11) {
                    viewMonth = 0;
                    viewYear++;
                }
                renderDays();
            } else if (currentMode === "months") {
                viewYear++;
                renderMonths();
            } else if (currentMode === "years") {
                viewYear += 20;
                renderYears();
            }
        });

        btnMonth.addEventListener("click", function (e) {
            e.stopPropagation();
            switchMode(currentMode === "months" ? "days" : "months");
        });

        btnYear.addEventListener("click", function (e) {
            e.stopPropagation();
            switchMode(currentMode === "years" ? "days" : "years");
        });

        if (btnApplyYear && inputYear) {
            btnApplyYear.addEventListener("click", function (e) {
                e.stopPropagation();
                const yVal = parseInt(inputYear.value);
                if (!isNaN(yVal) && yVal >= 1900 && yVal <= 2100) {
                    viewYear = yVal;
                    switchMode("days");
                }
            });
            inputYear.addEventListener("keydown", function (e) {
                if (e.key === "Enter") {
                    e.preventDefault();
                    btnApplyYear.click();
                }
            });
        }

        btnToday.addEventListener("click", function (e) {
            e.stopPropagation();
            const now = new Date();
            selectedDate = now;
            viewYear = now.getFullYear();
            viewMonth = now.getMonth();
            dateInput.value = formatYMD(selectedDate);
            displayInput.value = formatDisplay(selectedDate);
            dateInput.dispatchEvent(new Event("change", { bubbles: true }));
            dateInput.dispatchEvent(new Event("input", { bubbles: true }));
            renderDays();
            hideDatePicker();
        });

        if (btnClear) {
            btnClear.addEventListener("click", function (e) {
                e.stopPropagation();
                selectedDate = null;
                dateInput.value = "";
                displayInput.value = "";
                dateInput.dispatchEvent(new Event("change", { bubbles: true }));
                dateInput.dispatchEvent(new Event("input", { bubbles: true }));
                renderDays();
                hideDatePicker();
            });
        }

        btnClose.addEventListener("click", function (e) {
            e.stopPropagation();
            hideDatePicker();
        });

        // Trigger click
        displayInput.addEventListener("click", function (e) {
            e.stopPropagation();
            const isHidden = popup.classList.contains("hidden");
            closeAllCustomPopups(popup);

            if (isHidden) {
                if (dateInput.value && dateInput.value.includes("-")) {
                    const parts = dateInput.value.split("-");
                    selectedDate = new Date(parseInt(parts[0]), parseInt(parts[1]) - 1, parseInt(parts[2]));
                    viewYear = selectedDate.getFullYear();
                    viewMonth = selectedDate.getMonth();
                }
                switchMode("days");
                popup.classList.remove("hidden");
                elevateAncestors(container);

                displayInput.style.borderColor = "#e11d48";
                displayInput.style.boxShadow = "0 0 0 4px rgba(225, 29, 72, 0.12)";
                displayInput.style.backgroundColor = "#ffffff";

                // Auto position: check if popup overflows window bottom or right edge
                const rect = triggerWrapper.getBoundingClientRect();
                const spaceBelow = window.innerHeight - rect.bottom;
                const spaceAbove = rect.top;
                if (spaceBelow < 340 && spaceAbove > spaceBelow && spaceAbove > 280) {
                    popup.style.top = "auto";
                    popup.style.bottom = "calc(100% + 8px)";
                } else {
                    popup.style.top = "calc(100% + 8px)";
                    popup.style.bottom = "auto";
                }

                if (rect.left + 320 > window.innerWidth - 16) {
                    popup.style.left = "auto";
                    popup.style.right = "0";
                } else {
                    popup.style.left = "0";
                    popup.style.right = "auto";
                }
            } else {
                hideDatePicker();
            }
        });

        // Insert into DOM
        dateInput.parentNode.insertBefore(container, dateInput);
        container.appendChild(triggerWrapper);
        container.appendChild(popup);
        container.appendChild(dateInput);

        // Sync helper
        dateInput._syncCustomDate = function () {
            if (dateInput.value && dateInput.value.includes("-")) {
                const parts = dateInput.value.split("-");
                selectedDate = new Date(parseInt(parts[0]), parseInt(parts[1]) - 1, parseInt(parts[2]));
                displayInput.value = formatDisplay(selectedDate);
                viewYear = selectedDate.getFullYear();
                viewMonth = selectedDate.getMonth();
            } else {
                selectedDate = null;
                displayInput.value = "";
            }
            if (dateInput.classList.contains("hidden")) {
                container.classList.add("hidden");
            } else {
                container.classList.remove("hidden");
            }
        };

        dateInput.addEventListener("change", dateInput._syncCustomDate);
        dateInput.addEventListener("input", dateInput._syncCustomDate);
    }

    // =========================================================================
    // 3. AUTO INITIALIZE ALL CUSTOM UI
    // =========================================================================
    function initAllCustomUI() {
        document.querySelectorAll("select:not([multiple]):not([size]):not(.no-custom-select)").forEach(select => {
            initCustomSelect(select);
        });

        document.querySelectorAll("input[type='date']:not(.no-custom-datepicker)").forEach(input => {
            initCustomDatePicker(input);
        });
    }

    // Expose helpers to window
    window.CustomUI = {
        init: initAllCustomUI,
        initSelect: initCustomSelect,
        initDate: initCustomDatePicker,
        closeAll: closeAllCustomPopups,
        sync: function () {
            document.querySelectorAll("select[data-custom-ui-init='true']").forEach(s => {
                if (typeof s._syncCustomSelect === "function") s._syncCustomSelect();
            });
            document.querySelectorAll("input[data-custom-date-init='true']").forEach(d => {
                if (typeof d._syncCustomDate === "function") d._syncCustomDate();
            });
            initAllCustomUI();
        }
    };

    window.syncCustomUI = window.CustomUI.sync;

    if (document.readyState === "loading") {
        document.addEventListener("DOMContentLoaded", initAllCustomUI);
    } else {
        initAllCustomUI();
    }
})();
