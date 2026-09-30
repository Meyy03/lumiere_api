document.addEventListener(
    'DOMContentLoaded',
    () => {

        const body =
            document.body;

        // =====================================================
        // STAGGER KPI CARDS
        // =====================================================
        const kpiCards =
            document.querySelectorAll(
                '.report-kpi-card'
            );
        kpiCards.forEach(
            (card, index) => {
                card.style.setProperty(
                    '--animation-delay',
                    `${80 + index * 80}ms`
                );
            }
        );


        // =====================================================
        // STAGGER REPORT PANELS
        // =====================================================

        const panels =
            document.querySelectorAll(
                '.report-panel'
            );
        panels.forEach(
            (panel, index) => {
                panel.style.setProperty(
                    '--animation-delay',
                    `${180 + index * 90}ms`
                );
            }
        );


        // =====================================================
        // TABLE ROW ANIMATION
        // =====================================================

        const rows =
            document.querySelectorAll(
                '.report-table tbody tr'
            );
        rows.forEach(
            (row, index) => {
                row.style.setProperty(
                    '--row-delay',
                    `${320 + index * 45}ms`
                );
            }
        );


        // =====================================================
        // CATEGORY PROGRESS BARS
        // =====================================================

        const progressBars =
            document.querySelectorAll(
                '.category-progress-fill'
            );
        progressBars.forEach(
            (bar) => {
                const value =
                    Number(
                        bar.dataset.progress
                        ||
                        0
                    );


                const safeValue =
                    Math.max(
                        0,
                        Math.min(
                            100,
                            value
                        )
                    );


                bar.style.width =
                    '0%';

                bar.dataset.finalWidth =
                    `${safeValue}%`;
            }
        );


        // =====================================================
        // START PAGE ANIMATION
        // =====================================================

        requestAnimationFrame(
            () => {
                body.classList.add(
                    'reports-ready'
                );


                window.setTimeout(
                    () => {

                        progressBars.forEach(
                            (bar) => {

                                bar.style.width =
                                    bar.dataset.finalWidth;
                            }
                        );

                    },
                    450
                );

            }
        );


        // =====================================================
        // SELECT CHEVRON ACCESSIBILITY
        // =====================================================

        const rangeSelect =
            document.querySelector(
                '.range-select'
            );


        if (rangeSelect) {

            rangeSelect.addEventListener(
                'change',
                () => {

                    rangeSelect
                        .closest(
                            '.range-select-wrapper'
                        )
                        ?.classList
                        .add(
                            'range-changing'
                        );
                }
            );
        }

    }
);