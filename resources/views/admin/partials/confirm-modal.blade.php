<div id="lumiereConfirmOverlay" class="lumiere-modal-overlay" hidden>

    <div class="lumiere-modal" role="dialog" aria-modal="true" aria-labelledby="lumiereConfirmTitle"
        aria-describedby="lumiereConfirmMessage">

        <div class="modal-top-line"></div>


        <div class="modal-brand">
            LUMIÈRE ADMIN
        </div>


        <div class="modal-icon-wrapper">

            <div class="modal-icon" id="lumiereConfirmIcon"></div>

        </div>


        <h3 id="lumiereConfirmTitle" class="modal-title">
            Confirm Action
        </h3>


        <p id="lumiereConfirmMessage" class="modal-message">
            Are you sure you want to continue?
        </p>


        <div class="modal-divider"></div>


        <div class="modal-actions">

            <button type="button" id="lumiereConfirmCancel" class="modal-button modal-cancel">
                Cancel
            </button>


            <button type="button" id="lumiereConfirmSubmit" class="modal-button modal-confirm">
                Confirm
            </button>

        </div>

    </div>

</div>


<style>
    .lumiere-modal-overlay[hidden] {
        display: none !important;
    }


    .lumiere-modal-overlay {
        position: fixed;
        inset: 0;

        z-index: 999999;

        display: flex;
        align-items: center;
        justify-content: center;

        padding: 24px;

        background:
            rgba(33, 23, 29, 0.46);

        backdrop-filter: blur(5px);
        -webkit-backdrop-filter: blur(5px);
    }


    .lumiere-modal {
        position: relative;

        width: 100%;
        max-width: 410px;

        overflow: hidden;

        padding:
            31px 34px 28px;

        background:
            linear-gradient(180deg,
                #ffffff 0%,
                #fffafb 100%);

        border:
            1px solid #f0dce3;

        border-radius: 18px;

        text-align: center;

        box-shadow:
            0 28px 70px rgba(75, 41, 53, 0.23);

        animation:
            lumiereModalOpen 0.30s cubic-bezier(0.16,
                1,
                0.3,
                1);
    }


    @keyframes lumiereModalOpen {

        from {
            opacity: 0;

            transform:
                translateY(18px) scale(0.96);
        }

        to {
            opacity: 1;

            transform:
                translateY(0) scale(1);
        }

    }


    .modal-top-line {
        position: absolute;

        top: 0;
        left: 0;

        width: 100%;
        height: 4px;

        background:
            linear-gradient(90deg,
                #f2a1b8,
                #d94d78,
                #f2a1b8);
    }


    .modal-brand {
        margin-bottom: 18px;

        color: #d84d78;

        font-size: 10px;
        font-weight: 800;

        letter-spacing: 2.6px;
    }


    .modal-icon-wrapper {
        width: 70px;
        height: 70px;

        margin:
            0 auto 18px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 50%;

        background: #fff1f5;

        border:
            1px solid #f5ccd7;
    }


    .modal-icon {
        width: 42px;
        height: 42px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 50%;

        background: #ffffff;

        color: #d84d78;
    }


    .modal-icon svg {
        width: 21px;
        height: 21px;

        display: block;

        stroke: currentColor;
    }


    .modal-title {
        margin:
            0 0 9px;

        color: #292329;

        font-size: 23px;
        font-weight: 700;

        line-height: 1.25;
    }


    .modal-message {
        max-width: 325px;

        margin: 0 auto;

        color: #83777c;

        font-size: 13.5px;

        line-height: 1.65;
    }


    .modal-divider {
        height: 1px;

        margin:
            25px 0 20px;

        background: #f1e6e9;
    }


    .modal-actions {
        display: grid;

        grid-template-columns:
            1fr 1fr;

        gap: 11px;
    }


    .lumiere-modal-overlay.is-notice .modal-actions {
        grid-template-columns: 1fr;
    }


    .modal-button {
        height: 46px;

        border-radius: 10px;

        font-family: inherit;

        font-size: 13px;
        font-weight: 700;

        cursor: pointer;

        transition:
            transform 0.18s ease,
            box-shadow 0.18s ease,
            background 0.18s ease;
    }


    .modal-cancel {
        border:
            1px solid #ead8de;

        background: #ffffff;

        color: #64575c;
    }


    .modal-confirm {
        border: none;

        background:
            linear-gradient(90deg,
                #d94d78,
                #dc5c84);

        color: #ffffff;

        box-shadow:
            0 8px 19px rgba(213, 71, 114, 0.22);
    }


    .modal-button:hover {
        transform:
            translateY(-1px);
    }


    /* =========================================================
   SUCCESS
   ========================================================= */

    .lumiere-modal-overlay.is-success .modal-top-line {
        background:
            linear-gradient(90deg,
                #9bd8b8,
                #46af78,
                #9bd8b8);
    }


    .lumiere-modal-overlay.is-success .modal-icon-wrapper {
        background: #ecfaf2;

        border-color: #c9ecd8;
    }


    .lumiere-modal-overlay.is-success .modal-icon {
        color: #3eae72;
    }


    .lumiere-modal-overlay.is-success .modal-confirm {
        background:
            linear-gradient(90deg,
                #42aa70,
                #58ba82);
    }


    /* =========================================================
   ERROR
   ========================================================= */

    .lumiere-modal-overlay.is-error .modal-top-line {
        background:
            linear-gradient(90deg,
                #f3a4b6,
                #d83d62,
                #f3a4b6);
    }


    .lumiere-modal-overlay.is-error .modal-icon-wrapper {
        background: #fff0f3;

        border-color: #f4cbd5;
    }


    .lumiere-modal-overlay.is-error .modal-icon {
        color: #d83d62;
    }


    .lumiere-modal-overlay.is-error .modal-confirm {
        background:
            linear-gradient(90deg,
                #d83d62,
                #df5576);
    }


    /* =========================================================
   DELETE
   ========================================================= */

    .lumiere-modal-overlay.is-delete .modal-icon {
        color: #d83d62;
    }


    /* =========================================================
   LOGOUT
   ========================================================= */

    .lumiere-modal-overlay.is-logout .modal-icon {
        color: #cf4b74;
    }


    body.lumiere-modal-open {
        overflow: hidden;
    }


    @media (max-width: 520px) {

        .lumiere-modal {
            max-width: 360px;

            padding:
                28px 22px 22px;
        }


        .modal-actions {
            grid-template-columns: 1fr;
        }


        .modal-confirm {
            grid-row: 1;
        }


        .modal-cancel {
            grid-row: 2;
        }

    }
</style>


<script>

    document.addEventListener(
        'DOMContentLoaded',
        function () {


            const overlay =
                document.getElementById(
                    'lumiereConfirmOverlay'
                );


            if (!overlay) {
                return;
            }


            const title =
                document.getElementById(
                    'lumiereConfirmTitle'
                );


            const message =
                document.getElementById(
                    'lumiereConfirmMessage'
                );


            const icon =
                document.getElementById(
                    'lumiereConfirmIcon'
                );


            const cancelButton =
                document.getElementById(
                    'lumiereConfirmCancel'
                );


            const confirmButton =
                document.getElementById(
                    'lumiereConfirmSubmit'
                );


            let pendingForm =
                null;


            let currentNotice =
                null;


            /* =====================================================
               ICONS
               ===================================================== */

            const successIcon = `
            <svg
                viewBox="0 0 24 24"
                fill="none"
                stroke-width="2.2"
                stroke-linecap="round"
                stroke-linejoin="round"
            >
                <circle
                    cx="12"
                    cy="12"
                    r="9"
                ></circle>

                <path
                    d="M8 12.5l2.5 2.5L16.5 9"
                ></path>
            </svg>
        `;


            const errorIcon = `
            <svg
                viewBox="0 0 24 24"
                fill="none"
                stroke-width="2.2"
                stroke-linecap="round"
                stroke-linejoin="round"
            >
                <circle
                    cx="12"
                    cy="12"
                    r="9"
                ></circle>

                <path
                    d="M9 9l6 6"
                ></path>

                <path
                    d="M15 9l-6 6"
                ></path>
            </svg>
        `;


            const deleteIcon = `
            <svg
                viewBox="0 0 24 24"
                fill="none"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
            >
                <path d="M3 6h18"></path>

                <path d="M8 6V4h8v2"></path>

                <path
                    d="M19 6l-1 14H6L5 6"
                ></path>

                <path d="M10 11v5"></path>

                <path d="M14 11v5"></path>
            </svg>
        `;


            const createIcon = `
            <svg
                viewBox="0 0 24 24"
                fill="none"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
            >
                <circle
                    cx="12"
                    cy="12"
                    r="9"
                ></circle>

                <path d="M12 8v8"></path>

                <path d="M8 12h8"></path>
            </svg>
        `;


            const editIcon = `
            <svg
                viewBox="0 0 24 24"
                fill="none"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
            >
                <path d="M12 20h9"></path>

                <path
                    d="M16.5 3.5
                       a2.1 2.1 0 0 1 3 3
                       L8 18
                       l-4 1
                       1-4
                       Z"
                ></path>
            </svg>
        `;


            const logoutIcon = `
            <svg
                viewBox="0 0 24 24"
                fill="none"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
            >
                <path
                    d="M9 21H5
                       a2 2 0 0 1-2-2
                       V5
                       a2 2 0 0 1 2-2
                       h4"
                ></path>

                <path
                    d="M16 17l5-5-5-5"
                ></path>

                <path
                    d="M21 12H9"
                ></path>
            </svg>
        `;


            /* =====================================================
               RESET
               ===================================================== */

            function resetVisualState() {

                overlay.classList.remove(
                    'is-success',
                    'is-error',
                    'is-delete',
                    'is-create',
                    'is-edit',
                    'is-logout',
                    'is-notice'
                );


                cancelButton.hidden =
                    false;

            }


            /* =====================================================
               APPLY TYPE
               ===================================================== */

            function applyType(type) {

                if (type === 'success') {

                    overlay.classList.add(
                        'is-success'
                    );

                    icon.innerHTML =
                        successIcon;

                    return;
                }


                if (type === 'error') {

                    overlay.classList.add(
                        'is-error'
                    );

                    icon.innerHTML =
                        errorIcon;

                    return;
                }


                if (type === 'delete') {

                    overlay.classList.add(
                        'is-delete'
                    );

                    icon.innerHTML =
                        deleteIcon;

                    return;
                }


                if (type === 'logout') {

                    overlay.classList.add(
                        'is-logout'
                    );

                    icon.innerHTML =
                        logoutIcon;

                    return;
                }


                if (type === 'create') {

                    overlay.classList.add(
                        'is-create'
                    );

                    icon.innerHTML =
                        createIcon;

                    return;
                }


                overlay.classList.add(
                    'is-edit'
                );


                icon.innerHTML =
                    editIcon;

            }


            /* =====================================================
               OPEN FLASH NOTICE
               ===================================================== */

            function openNotice(data) {

                resetVisualState();


                pendingForm =
                    null;


                currentNotice =
                    data;


                overlay.classList.add(
                    'is-notice'
                );


                cancelButton.hidden =
                    true;


                const type =
                    data.type === 'error'
                        ? 'error'
                        : 'success';


                applyType(type);


                title.textContent =
                    data.title ||
                    'Message';


                message.textContent =
                    data.message ||
                    '';


                confirmButton.textContent =
                    data.button ||
                    'OK';


                overlay.hidden =
                    false;


                document.body.classList.add(
                    'lumiere-modal-open'
                );


                confirmButton.focus();

            }


            /* =====================================================
               OPEN FORM CONFIRM
               ===================================================== */

            function openFormModal(form) {

                resetVisualState();


                currentNotice =
                    null;


                pendingForm =
                    form;


                const declaredType =
                    form.dataset.confirmType ||
                    'edit';


                const modalTitle =
                    form.dataset.confirmTitle ||
                    'Confirm Action';


                let visualType =
                    declaredType;


                if (
                    declaredType === 'save'
                ) {

                    visualType =
                        modalTitle
                            .toLowerCase()
                            .includes('create')
                            ? 'create'
                            : 'edit';

                }


                applyType(
                    visualType
                );


                title.textContent =
                    modalTitle;


                message.textContent =
                    form.dataset.confirmMessage ||
                    'Are you sure you want to continue?';


                confirmButton.textContent =
                    form.dataset.confirmButton ||
                    'Confirm';


                overlay.hidden =
                    false;


                document.body.classList.add(
                    'lumiere-modal-open'
                );


                cancelButton.focus();

            }


            /* =====================================================
               CLOSE MODAL
               ===================================================== */

            function closeModal() {

                overlay.hidden =
                    true;


                document.body.classList.remove(
                    'lumiere-modal-open'
                );


                resetVisualState();


                pendingForm =
                    null;


                currentNotice =
                    null;

            }


            /* =====================================================
               CREATE / EDIT / DELETE / LOGOUT FORMS
               ===================================================== */

            document.addEventListener(
                'submit',
                function (event) {


                    const form =
                        event.target;


                    if (
                        !(
                            form instanceof
                            HTMLFormElement
                        )
                    ) {
                        return;
                    }


                    if (
                        !form.matches(
                            'form[data-confirm]'
                        )
                    ) {
                        return;
                    }


                    if (
                        form.dataset.confirmed ===
                        'true'
                    ) {

                        form.dataset.confirmed =
                            'false';

                        return;
                    }


                    event.preventDefault();


                    openFormModal(
                        form
                    );

                }
            );


            /* =====================================================
               CANCEL
               ===================================================== */

            cancelButton.addEventListener(
                'click',
                function () {

                    closeModal();

                }
            );


            /* =====================================================
               CONFIRM / CONTINUE / TRY AGAIN
               ===================================================== */

            confirmButton.addEventListener(
                'click',
                function () {


                    /*
                     * ---------------------------------------------
                     * FLASH MESSAGE
                     * ---------------------------------------------
                     */

                    if (
                        currentNotice
                    ) {

                        const notice =
                            currentNotice;


                        /*
                         * LOGIN SUCCESS
                         *
                         * Continue → Dashboard
                         */
                        if (
                            notice.redirect_url
                        ) {

                            window.location.href =
                                notice.redirect_url;

                            return;
                        }


                        /*
                         * LOGIN FAILED
                         *
                         * Try Again →
                         * close modal + clear Login fields.
                         */
                        if (
                            notice.clear_login
                        ) {

                            closeModal();


                            window.dispatchEvent(
                                new CustomEvent(
                                    'lumiere:clear-login'
                                )
                            );


                            return;
                        }


                        /*
                         * NORMAL SUCCESS MESSAGE,
                         * e.g. Logged Out Successfully.
                         */
                        closeModal();

                        return;

                    }


                    /*
                     * ---------------------------------------------
                     * FORM CONFIRMATION
                     * ---------------------------------------------
                     */

                    if (
                        pendingForm
                    ) {

                        const formToSubmit =
                            pendingForm;


                        pendingForm =
                            null;


                        overlay.hidden =
                            true;


                        document.body.classList.remove(
                            'lumiere-modal-open'
                        );


                        formToSubmit.dataset.confirmed =
                            'true';


                        formToSubmit.requestSubmit();

                    }

                }
            );


            /* =====================================================
               CLICK OUTSIDE
               ===================================================== */

            overlay.addEventListener(
                'click',
                function (event) {


                    if (
                        event.target !== overlay
                    ) {
                        return;
                    }


                    /*
                     * Force users to use the button for
                     * Login success / Login failed notices.
                     */
                    if (
                        currentNotice
                    ) {
                        return;
                    }


                    closeModal();

                }
            );


            /* =====================================================
               ESCAPE
               ===================================================== */

            document.addEventListener(
                'keydown',
                function (event) {


                    if (
                        event.key !== 'Escape' ||
                        overlay.hidden
                    ) {
                        return;
                    }


                    if (
                        currentNotice
                    ) {
                        return;
                    }


                    closeModal();

                }
            );


            /* =====================================================
               LARAVEL FLASH MESSAGE
               ===================================================== */

            const flashMessage =
                @json(
                    session(
                        'admin_feedback'
                    )
                );


            if (
                flashMessage &&
                typeof flashMessage ===
                'object'
            ) {

                setTimeout(
                    function () {

                        openNotice(
                            flashMessage
                        );

                    },
                    180
                );

            }

        }
    );

</script>