(() => {
    const iconNamespace = 'http://www.w3.org/2000/svg';
    const iconDefinitions = Object.freeze({
        add: '<path d="M12 5v14M5 12h14"/>',
        attachment: '<path d="M8 12.5 14.5 6a3 3 0 0 1 4.25 4.25L10 19a5 5 0 0 1-7-7l9-9"/><path d="m6 14 8-8"/>',
        back: '<path d="m15 18-6-6 6-6"/>',
        clear: '<path d="m7 7 10 10M17 7 7 17"/>',
        compose: '<path d="M12 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.375 2.625a1 1 0 0 1 3 3L12 15l-4 1 1-4Z"/>',
        edit: '<path d="M4 20h4L19 9l-4-4L4 16v4Z"/><path d="m13.5 6.5 4 4"/>',
        contacts: '<circle cx="9" cy="8" r="3"/><path d="M3 20v-2a6 6 0 0 1 12 0v2M16 4a3 3 0 0 1 0 6M17 14a5 5 0 0 1 4 4v2"/>',
        delete: '<path d="M4 7h16M9 7V4h6v3M7 7l1 13h8l1-13M10 11v5M14 11v5"/>',
        draft: '<path d="M5 3h10l4 4v14H5V3Z"/><path d="M14 3v5h5M8 12h8M8 16h6"/>',
        folder: '<path d="M3 6h7l2 2h9v11H3V6Z"/>',
        forward: '<path d="m14 7 5 5-5 5"/><path d="M19 12H9a5 5 0 0 0-5 5"/>',
        external: '<path d="M14 4h6v6M20 4l-9 9"/><path d="M18 13v7H4V6h7"/>',
        inbox: '<path d="M4 5h16v14H4V5Z"/><path d="M4 14h4l2 2h4l2-2h4"/>',
        mark: '<rect x="3" y="5" width="18" height="14" rx="2"/><path d="m4 7 8 6 8-6"/>',
        html: '<path d="m8 9-3 3 3 3M16 9l3 3-3 3M14 5l-4 14"/>',
        info: '<circle cx="12" cy="12" r="9"/><path d="M12 11v6M12 7h.01"/>',
        menu: '<path d="M4 6h16M4 12h16M4 18h16"/>',
        more: '<circle cx="5" cy="12" r="1"/><circle cx="12" cy="12" r="1"/><circle cx="19" cy="12" r="1"/>',
        next: '<path d="m9 18 6-6-6-6"/>',
        logout: '<path d="M10 5H5v14h5M14 8l4 4-4 4M18 12H9"/>',
        options: '<path d="M4 7h10M18 7h2M4 17h2M10 17h10"/><circle cx="16" cy="7" r="2"/><circle cx="8" cy="17" r="2"/>',
        previous: '<path d="m15 18-6-6 6-6"/>',
        refresh: '<path d="M20 7v5h-5"/><path d="M4 17v-5h5"/><path d="M6.1 8a7 7 0 0 1 11.2-2L20 9M4 15l2.7 3a7 7 0 0 0 11.2-2"/>',
        reply: '<path d="m10 7-5 5 5 5"/><path d="M5 12h9a6 6 0 0 1 6 6"/>',
        replyAll: '<path d="m9 8-4 4 4 4M14 8l-4 4 4 4"/><path d="M10 12h5a5 5 0 0 1 5 5"/>',
        response: '<path d="m10 7-5 5 5 5"/><path d="M5 12h10a5 5 0 0 1 5 5"/><path d="M15 5h4v4"/>',
        search: '<circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/>',
        select: '<rect x="4" y="4" width="16" height="16" rx="2"/><path d="m8 12 3 3 5-6"/>',
        signature: '<path d="M4 17c3-5 4-9 6-9 3 0-1 8 2 8 2 0 3-4 5-4 2 0 0 4 3 4M4 21h16"/>',
        spell: '<path d="M4 5h6M7 5v10M4 15h6M14 6l2 9 2-9M13 12h6"/><path d="m15 19 2 2 4-5"/>',
        sent: '<path d="m3 11 18-8-8 18-2-7-8-3Z"/><path d="M11 14 21 3"/>',
        settings: '<circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.8 1.8 0 0 0 .36 2l.05.05-2.83 2.83-.05-.05a1.8 1.8 0 0 0-2-.36 1.8 1.8 0 0 0-1.1 1.65V21h-4v-.08A1.8 1.8 0 0 0 8.7 19.3a1.8 1.8 0 0 0-2 .36l-.05.05-2.83-2.83.05-.05a1.8 1.8 0 0 0 .36-2A1.8 1.8 0 0 0 2.58 13H2v-4h.58A1.8 1.8 0 0 0 4.2 7.9a1.8 1.8 0 0 0-.36-2l-.05-.05L6.62 3l.05.05a1.8 1.8 0 0 0 2 .36A1.8 1.8 0 0 0 9.8 1.8V2h4v-.2a1.8 1.8 0 0 0 1.1 1.65 1.8 1.8 0 0 0 2-.36l.05-.05 2.83 2.83-.05.05a1.8 1.8 0 0 0-.36 2A1.8 1.8 0 0 0 21 9h1v4h-1a1.8 1.8 0 0 0-1.6 2Z"/>',
        spam: '<circle cx="12" cy="12" r="9"/><path d="M12 7v6M12 17h.01"/>',
        star: '<path d="m12 3 2.8 5.7 6.2.9-4.5 4.4 1.1 6.2-5.6-2.9-5.6 2.9 1.1-6.2L3 9.6l6.2-.9L12 3Z"/>',
        trash: '<path d="M4 7h16M9 7V4h6v3M7 7l1 13h8l1-13"/>',
        language: '<circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3c3 3 3 15 0 18M12 3c-3 3-3 15 0 18"/>',
    });

    const readerStates = new Set(['empty', 'loading', 'ready', 'error']);
    const resetActions = new Set([
        'delete',
        'list',
        'move',
        'nextpage',
        'previouspage',
        'refresh',
        'reset-search',
        'search',
        'sort',
    ]);

    let activeUid = null;
    let activeRow = null;
    let observedList = null;

    const createIcon = (name) => {
        const markup = iconDefinitions[name];

        if (!markup) {
            return null;
        }

        const icon = document.createElementNS(iconNamespace, 'svg');
        icon.classList.add('refi-icon', `refi-icon-${name}`);
        icon.setAttribute('viewBox', '0 0 24 24');
        icon.setAttribute('fill', 'none');
        icon.setAttribute('stroke', 'currentColor');
        icon.setAttribute('stroke-width', '2');
        icon.setAttribute('stroke-linecap', 'round');
        icon.setAttribute('stroke-linejoin', 'round');
        icon.setAttribute('aria-hidden', 'true');
        icon.innerHTML = markup;

        return icon;
    };

    const installIcon = (element, name, label, showLabel = false) => {
        if (!element) {
            return;
        }

        element.classList.add('refi-icon-button');
        element.classList.toggle('refi-icon-with-label', showLabel);
        element.classList.toggle('refi-icon-only', !showLabel);

        if (label) {
            element.setAttribute('aria-label', label);
            element.title = label;
        }

        if (!element.querySelector(':scope > .refi-icon')) {
            const icon = createIcon(name);

            if (icon) {
                element.prepend(icon);
            }
        }
    };
    const installIcons = (selector, name, label, showLabel = false) => {
        document.querySelectorAll(selector).forEach((element) => {
            installIcon(element, name, label, showLabel);
        });
    };


    const installInlineIcon = (element, name, label) => {
        if (!element) {
            return;
        }

        element.classList.add('refi-icon-host');

        if (label) {
            element.setAttribute('aria-label', label);
            element.title = label;
        }

        if (!element.querySelector(':scope > .refi-icon')) {
            const icon = createIcon(name);

            if (icon) {
                element.prepend(icon);
            }
        }
    };

    const setAccountInitials = () => {
        const accountName = document.querySelector('#refi-account-menu .refi-account-menu-name')?.textContent.trim();
        const avatar = document.querySelector('.refi-account-avatar');

        if (!accountName || !avatar) {
            return;
        }

        const localPart = accountName.split('@')[0];
        const parts = localPart.split(/[^a-z0-9]+/i).filter(Boolean);
        const initials = (parts.length > 1 ? `${parts[0][0]}${parts[1][0]}` : localPart.slice(0, 2)).toUpperCase();
        avatar.textContent = initials || 'RM';
    };

    const decorateFolders = () => {
        const folderIcons = {
            drafts: 'draft',
            inbox: 'inbox',
            junk: 'spam',
            sent: 'sent',
            trash: 'trash',
        };

        document.querySelectorAll('#mailboxlist li.mailbox').forEach((item) => {
            const iconName = Object.keys(folderIcons).find((className) => item.classList.contains(className)) ?? 'folder';
            installIcon(item.querySelector(':scope > a'), folderIcons[iconName] ?? iconName, null, true);
        });

        installIcon(document.querySelector('.refi-taskmenu-links .contacts'), 'contacts', null, true);
        installIcon(document.querySelector('.refi-taskmenu-links .folders'), 'folder', null, true);
        installIcon(document.querySelector('.refi-taskmenu-links .settings'), 'settings', null, true);
    };

    const decorateTaskNavigation = () => {
        installIcon(document.querySelector('#taskmenu .compose'), 'compose', 'Tulis email', true);
        installIcon(document.querySelector('#taskmenu .mail'), 'inbox', 'Email', true);
        installIcon(document.querySelector('#taskmenu .contacts'), 'contacts', 'Kontak', true);
        installIcon(document.querySelector('#taskmenu .settings'), 'settings', 'Pengaturan', true);
        installIcon(document.querySelector('#taskmenu .about'), 'info', 'Informasi', true);
        installIcon(document.querySelector('#taskmenu .logout'), 'logout', 'Keluar', true);
        const visibleLabels = {
            about: 'Informasi',
            compose: 'Tulis',
            contacts: 'Kontak',
            logout: 'Keluar',
            mail: 'Email',
            settings: 'Pengaturan',
        };

        Object.entries(visibleLabels).forEach(([className, label]) => {
            const text = document.querySelector(`#taskmenu .${className} > .inner`);

            if (text) {
                text.textContent = label;
            }
        });


        document.querySelectorAll('input[placeholder="Search..."]').forEach((input) => {
            input.setAttribute('placeholder', window.rcmail.task === 'addressbook' ? 'Cari kontak' : 'Cari');
        });

        if (window.rcmail.task === 'addressbook') {
            const contactFrame = document.getElementById('contact-frame');
            const useLightFrame = () => {
                if (!contactFrame?.contentDocument?.body) {
                    return;
                }

                contactFrame.contentDocument.documentElement.classList.remove('dark-mode');
                contactFrame.contentDocument.body.style.background = '#fff';
            };

            contactFrame?.addEventListener('load', useLightFrame);
            useLightFrame();
        }
    };
    const decorateComposeControls = () => {
        document.querySelectorAll('#compose-headers .edit').forEach((button) => {
            installIcon(button, 'edit', 'Ubah identitas');
        });
        document.querySelectorAll('#compose-headers .add.recipient').forEach((button) => {
            installIcon(button, 'contacts', 'Tambah kontak');
        });
        document.querySelectorAll('#compose-headers .add.active:not(.recipient)').forEach((button) => {
            installIcon(button, 'add', 'Tambah Cc atau Bcc');
        });

        installIcon(document.querySelector('.mce-i-html'), 'html', 'Mode HTML');
        installIcon(document.querySelector('.formbuttons .extwin'), 'external', 'Buka dalam jendela baru', true);
        installIcon(document.getElementById('rcmbtn106-clone'), 'draft', 'Simpan draft', true);
        installIcon(document.getElementById('rcmbtn111-clone'), 'sent', 'Kirim', true);
        installIcon(document.querySelector('body.action-compose #layout-content > .header .task-menu-button'), 'menu', 'Buka menu');
        installIcon(document.querySelector('body.action-compose #layout-content > .header .toolbar-menu-button'), 'more', 'Tindakan lainnya');
    };


    const decorateRows = () => {
        document.querySelectorAll('#messagelist tr.message').forEach((row) => {
            const checkbox = row.querySelector('td.selection input[type="checkbox"]');
            const flag = row.querySelector('td.flags .flag > span');
            const attachment = row.querySelector('td.flags .attachment[title]');

            if (checkbox) {
                checkbox.setAttribute('aria-label', 'Pilih email');
                checkbox.tabIndex = 0;
            }

            if (flag) {
                installInlineIcon(flag, 'star', flag.classList.contains('flagged') ? 'Hapus tanda bintang' : 'Tambahkan tanda bintang');

                if (!flag.dataset.refiKeyboardBound) {
                    flag.dataset.refiKeyboardBound = 'true';
                    flag.setAttribute('role', 'button');
                    flag.tabIndex = 0;
                    flag.addEventListener('keydown', (event) => {
                        if (event.key === 'Enter' || event.key === ' ') {
                            event.preventDefault();
                            flag.click();
                        }
                    });
                }
            }

            installInlineIcon(attachment, 'attachment', 'Memiliki lampiran');
        });
    };

    const formatPagination = () => {
        const count = document.getElementById('rcmcountdisplay');

        if (!count) {
            return;
        }

        count.closest('li')?.classList.add('refi-page-count-item');

        const match = count.textContent.trim().match(/Pesan dari (\d+) sampai (\d+) dari total (\d+)/i);

        if (match) {
            count.textContent = `${match[1]}–${match[2]} dari ${match[3]}`;
        }
    };

    const decorateInterface = () => {
        const searchbar = document.querySelector('.refi-appbar-search .searchbar');

        if (searchbar && !searchbar.querySelector(':scope > .refi-search-icon')) {
            const icon = createIcon('search');

            if (icon) {
                icon.classList.add('refi-search-icon');
                searchbar.prepend(icon);
            }
        }

        installIcon(document.querySelector('.refi-appbar-search .reset'), 'clear', 'Hapus pencarian');
        installIcon(document.querySelector('.refi-appbar-search .options'), 'options', 'Filter pencarian');
        installIcon(document.querySelector('.refi-appbar-start .back-sidebar-button'), 'menu', 'Buka folder');
        installIcon(document.querySelector('#taskmenu .compose'), 'compose', 'Tulis email', true);

        decorateFolders();
        decorateRows();
        setAccountInitials();
        formatPagination();

        installIcon(document.querySelector('#messagelist-header .select'), 'select', 'Pilih email');
        installIcon(document.querySelector('#messagelist-header .refresh'), 'refresh', 'Periksa email baru');
        installIcon(document.querySelector('#messagelist-header .more'), 'more', 'Tindakan lainnya');
        installIcon(document.querySelector('#messagelist-header .back-sidebar-button'), 'menu', 'Buka folder');
        installIcon(document.querySelector('#messagelist-header .toolbar-menu-button'), 'more', 'Tindakan lainnya');

        installIcon(document.querySelector('#messagelist-header .firstpage'), 'previous', 'Halaman pertama');
        installIcon(document.querySelector('#messagelist-header .prevpage'), 'previous', 'Halaman sebelumnya');
        installIcon(document.querySelector('#messagelist-header .nextpage'), 'next', 'Halaman berikutnya');
        installIcon(document.querySelector('#messagelist-header .lastpage'), 'next', 'Halaman terakhir');

        installIcon(document.querySelector('.refi-message-toolbar .back-list-button'), 'back', 'Kembali ke daftar');
        installIcons('#mailtoolbar .reply, #toolbar-menu > li > .reply', 'reply', 'Balas');
        installIcons('#mailtoolbar .reply-all, #toolbar-menu > li > .dropbutton > .reply-all', 'replyAll', 'Balas ke semua');
        installIcons('#mailtoolbar .forward, #toolbar-menu > li > .dropbutton > .forward', 'forward', 'Teruskan');
        installIcons('#mailtoolbar .delete, #toolbar-menu > li > .delete', 'delete', 'Hapus');
        installIcons('#markmessagemenulink', 'mark', 'Tandai pesan');
        installIcons('#messagemenulink, .refi-message-toolbar .toolbar-menu-button', 'more', 'Tindakan lainnya');

        installIcon(document.querySelector('.refi-message-actions .refi-reply-action'), 'reply', 'Balas', true);
        installIcon(document.querySelector('.refi-message-actions .refi-forward-action'), 'forward', 'Teruskan', true);

        installIcon(document.querySelector('body.action-compose #toolbar-menu .save'), 'draft', 'Simpan draft');
        installIcon(document.querySelector('body.action-compose #toolbar-menu .attach'), 'attachment', 'Lampirkan file');
        installIcon(document.querySelector('body.action-compose #toolbar-menu .options'), 'options', 'Opsi lainnya');
        installIcon(document.querySelector('body.action-compose #toolbar-menu .signature'), 'signature', 'Tanda tangan');
        installIcon(document.querySelector('body.action-compose #toolbar-menu .responses'), 'response', 'Respons tersimpan');
        installIcon(document.querySelector('body.action-compose #toolbar-menu .spellcheck'), 'spell', 'Periksa ejaan');
        installIcon(document.querySelector('body.action-compose #toolbar-menu .dropdown'), 'language', 'Bahasa');
        installIcon(document.querySelector('body.action-compose button.send'), 'sent', 'Kirim', true);
        decorateComposeControls();
    };

    const readerFrame = () => document.getElementById('messagecontframe');

    const setReaderState = (state, uid = null) => {
        if (!readerStates.has(state)) {
            return;
        }

        if (uid !== null) {
            activeUid = String(uid);
        } else if (state === 'empty') {
            activeUid = null;
        }

        document.body.dataset.refiReaderState = state;

        const emptyPreview = document.querySelector('.refi-empty-preview');

        if (emptyPreview) {
            emptyPreview.setAttribute('aria-hidden', state === 'empty' ? 'false' : 'true');
        }

        const frame = readerFrame();

        if (frame) {
            frame.setAttribute('aria-busy', state === 'loading' ? 'true' : 'false');
        }
    };

    const resetReader = () => {
        activeRow = null;
        setReaderState('empty');
    };

    const selectedUid = (list) => {
        if (!list || typeof list.get_selection !== 'function' || typeof list.get_single_selection !== 'function') {
            return null;
        }

        const selection = list.get_selection(false);

        return selection.length === 1 ? list.get_single_selection() : null;
    };

    const selectMessage = (list) => {
        const uid = selectedUid(list);

        if (!uid || list.multi_selecting) {
            resetReader();

            return;
        }

        activeRow = list.rows?.[uid]?.obj ?? null;
        setReaderState('loading', uid);
    };

    const localisePreview = (frame) => {
        const documentInFrame = frame?.contentDocument;

        if (!documentInFrame?.body) {
            return;
        }


        const localise = () => {
            documentInFrame.querySelectorAll('a, button').forEach((element) => {
                if (element.textContent.trim() === 'Details') {
                    element.textContent = 'Detail';
                }

                if (element.textContent.trim() === 'Headers') {
                    element.textContent = 'Header';
                }
            });

            const summary = documentInFrame.querySelector('.header-summary');

            if (summary) {
                const walker = documentInFrame.createTreeWalker(summary, NodeFilter.SHOW_TEXT);
                let textNode = walker.nextNode();

                while (textNode) {
                    textNode.nodeValue = textNode.nodeValue
                        .replace(/^From\s+/, 'Dari ')
                        .replace(/^\s+on\s+$/, ' pada ');
                    textNode = walker.nextNode();
                }
            }
        };

        localise();
        frame.contentWindow.requestAnimationFrame(localise);
        frame.contentWindow.setTimeout(localise, 100);
    };

    const frameLoaded = () => {
        if (!activeUid) {
            return;
        }

        const frame = readerFrame();
        const previewClient = frame?.contentWindow?.rcmail;

        if (previewClient?.env?.action === 'preview' && String(previewClient.env.uid) === activeUid) {
            localisePreview(frame);
            setReaderState('ready', activeUid);

            return;
        }

        if (previewClient?.env?.action && previewClient.env.action !== 'preview') {
            setReaderState('error', activeUid);
        }
    };

    const retryMessage = () => {
        if (!activeUid || !window.rcmail?.show_message) {
            return;
        }

        setReaderState('loading', activeUid);
        window.rcmail.show_message(activeUid, false, true);
    };

    const returnToList = (event) => {
        if (!window.matchMedia('(min-width: 769px)').matches || !window.rcmail?.message_list) {
            return;
        }

        event.preventDefault();
        const rowToFocus = activeRow;

        window.rcmail.message_list.clear_selection();
        window.rcmail.show_contentframe(false);
        resetReader();
        rowToFocus?.querySelector('[tabindex="0"]')?.focus();
    };

    const refreshListPresentation = () => {
        window.requestAnimationFrame(() => {
            decorateRows();
            formatPagination();
        });
    };

    const bindMailbox = () => {
        const list = window.rcmail?.message_list;
        const frame = readerFrame();

        if (!list || !frame || observedList === list) {
            return;
        }

        observedList = list;
        setReaderState('empty');
        list.addEventListener('select', selectMessage);
        frame.addEventListener('load', frameLoaded);

        window.rcmail.addEventListener('listupdate', () => {
            resetReader();
            refreshListPresentation();
        });
        window.rcmail.addEventListener('selectfolder', resetReader);
        window.rcmail.addEventListener('actionbefore', (event) => {
            if (resetActions.has(event.action)) {
                resetReader();
            }
        });

        document.querySelector('.refi-message-toolbar .back-list-button')?.addEventListener('click', returnToList);
        document.querySelector('[data-refi-reader-retry]')?.addEventListener('click', retryMessage);
    };

    const bindComposeOptions = () => {
        document.addEventListener('click', (event) => {
            const button = event.target.closest('body.task-mail.action-compose .options[href="#options"]');

            if (!button) {
                return;
            }

            event.preventDefault();
            event.stopImmediatePropagation();
            document.body.classList.toggle('refi-compose-options-open');
        }, true);

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                document.body.classList.remove('refi-compose-options-open');
            }
        });
    };

    const localiseShell = () => {
        const search = document.getElementById('mailsearchform');

        if (search) {
            search.setAttribute('placeholder', 'Cari email');
        }

        const compose = document.querySelector('#taskmenu .compose .inner');

        if (compose) {
            compose.textContent = 'Tulis';
        }

        const contacts = document.querySelector('.refi-taskmenu-links .contacts .inner');
        const folders = document.querySelector('.refi-taskmenu-links .folders .inner');

        if (contacts) {
            contacts.textContent = 'Kontak';
        }

        if (folders) {
            folders.textContent = 'Kelola folder';
        }

        const moreActions = document.getElementById('messagemenulink');

        if (moreActions) {
            moreActions.title = 'Tindakan lainnya';
            moreActions.querySelector('.inner').textContent = 'Lainnya';
        }

        const listMoreActions = document.getElementById('listmenulink');

        if (listMoreActions) {
            listMoreActions.title = 'Tindakan lainnya';
            listMoreActions.querySelector('.inner').textContent = 'Lainnya';
        }

        const downloadAction = document.querySelector('#message-menu .download span');

        if (downloadAction) {
            downloadAction.textContent = 'Unduh pesan';
        }
    };

    const initialise = () => {
        if (!window.rcmail) {
            return;
        }

        decorateTaskNavigation();

        if (window.rcmail.task !== 'mail') {
            return;
        }

        localiseShell();
        decorateInterface();
        window.setTimeout(() => {
            decorateInterface();
            formatPagination();
        }, 100);

        if (window.rcmail.env.action === 'compose') {
            bindComposeOptions();

            return;
        }

        if (window.rcmail.env.action === 'none' || window.rcmail.env.action === '') {
            bindMailbox();
        }
    };

    const bootstrap = () => {
        if (!window.rcmail) {
            return;
        }

        if (window.rcmail.loaded) {
            initialise();
        } else {
            window.rcmail.addEventListener('init', initialise);
        }
    };
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', bootstrap, { once: true });
    } else {
        bootstrap();
    }
})();
