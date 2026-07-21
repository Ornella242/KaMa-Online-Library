    <style>
        .categories-page {
            display: grid;
            gap: 22px
        }

        .categories-heading {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 20px;
            background: transparent;
            z-index: auto;
        }

        .categories-heading>div>span {
            display: block;
            margin-bottom: 4px;
            color: #b30000;
            font-size: .8rem;
            font-weight: 800;
            letter-spacing: .08em;
            text-transform: uppercase
        }

        .categories-heading h2 {
            margin: 0;
            font-size: 1.75rem
        }

        .categories-heading p {
            margin: 5px 0 0;
            color: #777b83;
            font-size: .95rem
        }

        .categories-add {
            display: inline-flex;
            min-height: 43px;
            flex-shrink: 0;
            align-items: center;
            gap: 8px;
            padding: 9px 15px;
            border: 0;
            border-radius: 10px;
            background: #b30000;
            color: #fff;
            font-size: .88rem;
            font-weight: 700
        }

        .categories-add:hover {
            background: #8f0000;
            color: #fff
        }

        .categories-stats {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 13px
        }

        .categories-stats article {
            display: flex;
            align-items: center;
            gap: 13px;
            padding: 16px;
            border: 1px solid #e6e7ea;
            border-radius: 14px;
            background: #fff;
            box-shadow: 0 8px 24px rgba(15, 23, 42, .035)
        }

        .categories-stats article>span {
            display: grid;
            width: 46px;
            height: 46px;
            flex: 0 0 46px;
            place-items: center;
            border-radius: 12px;
            font-size: 1.25rem
        }

        .categories-stats .total {
            background: #fff0f0;
            color: #b30000
        }

        .categories-stats .sub {
            background: #e8f5ff;
            color: #157bb5
        }

        .categories-stats .books {
            background: #eaf8ef;
            color: #138443
        }

        .categories-stats .empty {
            background: #f0f1f3;
            color: #4d5056
        }

        .categories-stats small,
        .categories-stats strong {
            display: block
        }

        .categories-stats small {
            color: #858991;
            font-size: .78rem;
            font-weight: 700
        }

        .categories-stats strong {
            margin-top: 2px;
            font-size: 1.4rem
        }

        .categories-panel {
            overflow: hidden;
            border: 1px solid #e5e7ea;
            border-radius: 17px;
            background: #fff;
            box-shadow: 0 8px 24px rgba(15, 23, 42, .035)
        }

        .categories-toolbar {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 17px 19px;
            border-bottom: 1px solid #eceef0;
            background: #fafafa
        }

        .categories-search {
            position: relative;
            display: flex;
            min-width: 240px;
            max-width: 460px;
            flex: 1;
            align-items: center
        }

        .categories-search i {
            position: absolute;
            left: 13px;
            color: #8c9097;
            font-size: .95rem
        }

        .categories-search input {
            width: 100%;
            height: 42px;
            padding: 8px 13px 8px 38px;
            border: 1px solid #dfe1e5;
            border-radius: 10px;
            background: #fff;
            color: #33363b;
            font-size: .88rem;
            outline: none
        }

        .categories-search input:focus {
            border-color: #b30000;
            box-shadow: 0 0 0 3px rgba(179, 0, 0, .08)
        }

        .categories-filter-btn {
            height: 42px;
            padding: 8px 18px;
            border: 0;
            border-radius: 10px;
            background: #1c1d20;
            color: #fff;
            font-size: .85rem;
            font-weight: 700
        }

        .categories-reset {
            display: inline-flex;
            height: 42px;
            align-items: center;
            gap: 6px;
            padding: 8px 11px;
            border: 1px solid #e0e2e5;
            border-radius: 10px;
            color: #777b82;
            font-size: .82rem;
            font-weight: 700
        }

        .categories-panel-header {
            padding: 16px 19px 9px;
            background: transparent;
            z-index: auto;
        }

        .categories-panel-header strong,
        .categories-panel-header span {
            display: block
        }

        .categories-panel-header strong {
            font-size: .95rem
        }

        .categories-panel-header span {
            margin-top: 2px;
            color: #989ba2;
            font-size: .78rem
        }

        .categories-table {
            min-width: 900px;
            margin: 0
        }

        .categories-table thead th {
            padding: 11px 14px;
            border-color: #eceef0;
            color: #8e9299;
            font-size: .74rem;
            font-weight: 700;
            letter-spacing: .05em;
            text-transform: uppercase;
            white-space: nowrap
        }

        .categories-table tbody td {
            padding: 13px 14px;
            border-color: #eff0f2;
            color: #45484e;
            font-size: .86rem
        }

        .categories-table tbody tr:hover {
            background: #fcfcfd
        }

        .categories-name-cell {
            display: flex;
            min-width: 190px;
            align-items: center;
            gap: 11px
        }

        .categories-name-cell>span {
            display: grid;
            width: 39px;
            height: 39px;
            flex: 0 0 39px;
            place-items: center;
            border-radius: 10px;
            background: #fff0f0;
            color: #b30000;
            font-size: 1rem
        }

        .categories-name-cell strong,
        .categories-name-cell small {
            display: block
        }

        .categories-name-cell strong {
            color: #23252a;
            font-size: .9rem
        }

        .categories-name-cell small {
            margin-top: 2px;
            color: #989ba2;
            font-size: .73rem
        }

        .categories-count-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 10px;
            border-radius: 999px;
            background: #f0f1f3;
            color: #4d5056;
            font-size: .76rem;
            font-weight: 700;
            white-space: nowrap
        }

        .categories-count-badge.filled {
            background: #fff0f0;
            color: #b30000
        }

        .categories-sub-cell {
            display: flex;
            max-width: 380px;
            flex-wrap: wrap;
            gap: 5px
        }

        .categories-sub-cell span {
            padding: 4px 9px;
            border: 1px solid #e6e7ea;
            border-radius: 999px;
            background: #fafafa;
            color: #55585e;
            font-size: .73rem;
            font-weight: 600
        }

        .categories-sub-cell span.more {
            border-color: #b30000;
            background: #fff0f0;
            color: #b30000
        }

        .categories-sub-cell em {
            color: #9a9da4;
            font-size: .78rem
        }

        .categories-date strong,
        .categories-date small {
            display: block
        }

        .categories-date strong {
            color: #3a3d42;
            font-size: .85rem
        }

        .categories-date small {
            margin-top: 3px;
            color: #989ba2;
            font-size: .73rem
        }

        .categories-row-actions {
            display: flex;
            justify-content: flex-end;
            gap: 6px
        }

        .categories-row-actions button {
            display: inline-flex;
            min-height: 33px;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 6px 11px;
            border: 1px solid transparent;
            border-radius: 8px;
            font-size: .78rem;
            font-weight: 700
        }

        .categories-row-actions .edit {
            border-color: #e2e4e7;
            background: #fff;
            color: #3f4248
        }

        .categories-row-actions .edit:hover {
            border-color: #1c1d20;
            background: #1c1d20;
            color: #fff
        }

        .categories-row-actions .delete {
            width: 33px;
            padding: 0;
            background: #fff0f0;
            color: #b30000
        }

        .categories-row-actions .delete:hover {
            background: #b30000;
            color: #fff
        }

        .categories-empty {
            display: grid;
            min-height: 280px;
            place-items: center;
            align-content: center;
            text-align: center
        }

        .categories-empty>span {
            display: grid;
            width: 60px;
            height: 60px;
            place-items: center;
            border-radius: 17px;
            background: #fff0f0;
            color: #b30000;
            font-size: 1.5rem
        }

        .categories-empty h3 {
            margin: 14px 0 4px;
            font-size: 1.12rem
        }

        .categories-empty p {
            margin: 0;
            color: #8c9097;
            font-size: .88rem
        }

        .categories-empty a {
            margin-top: 12px;
            color: #b30000;
            font-size: .85rem;
            font-weight: 700
        }

        .categories-pagination {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 15px 19px;
            border-top: 1px solid #eceef0;
            background: transparent;
            z-index: auto;
        }

        .categories-pagination>span {
            color: #8b8f96;
            font-size: .8rem
        }

        .categories-pagination .pagination {
            margin: 0
        }

        /* Overlays (même famille que la file éditoriale) */
        .categories-overlay {
            position: fixed;
            inset: 0;
            z-index: 10060;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            background: rgba(8, 10, 14, .72);
            backdrop-filter: blur(6px)
        }

        .categories-overlay-container {
            display: flex;
            width: min(100%, 590px);
            max-height: 94vh;
            overflow: hidden;
            flex-direction: column;
            border-radius: 18px;
            background: #fff;
            box-shadow: 0 28px 90px rgba(0, 0, 0, .36)
        }

        .categories-overlay-container>form {
            display: flex;
            min-height: 0;
            flex-direction: column
        }

        .categories-overlay-header {
            display: flex;
            flex-shrink: 0;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            padding: 17px 21px;
            background: #8f0000;
            color: #fff;
            z-index: auto;
        }

        .categories-overlay-header.dark {
            background: #111827
        }

        .categories-overlay-header.danger {
            background: #8f0000
        }

        .categories-overlay-header span {
            display: block;
            margin-bottom: 3px;
            color: #fca5a5;
            font-size: .72rem;
            font-weight: 800;
            letter-spacing: .08em;
            text-transform: uppercase
        }

        .categories-overlay-header h2 {
            max-width: 440px;
            margin: 0;
            overflow: hidden;
            color: #fff;
            font-size: 1.15rem;
            text-overflow: ellipsis;
            white-space: nowrap
        }

        .categories-overlay-header>button {
            display: grid;
            width: 35px;
            height: 35px;
            flex: 0 0 35px;
            place-items: center;
            border: 0;
            border-radius: 9px;
            background: rgba(255, 255, 255, .12);
            color: #fff
        }

        .categories-form-body {
            overflow: auto;
            padding: 24px
        }

        .categories-form-intro {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
            padding: 12px;
            border-radius: 11px;
            background: #f7f7f8
        }

        .categories-form-intro>span {
            display: grid;
            width: 43px;
            height: 43px;
            flex: 0 0 43px;
            place-items: center;
            border-radius: 11px;
            background: #fff0f0;
            color: #b30000;
            font-size: 1.1rem
        }

        .categories-form-intro small,
        .categories-form-intro strong,
        .categories-form-intro>div>span {
            display: block
        }

        .categories-form-intro small {
            color: #9699a0;
            font-size: .7rem;
            text-transform: uppercase
        }

        .categories-form-intro strong {
            margin-top: 2px;
            font-size: .88rem
        }

        .categories-form-intro>div>span {
            margin-top: 2px;
            color: #878b92;
            font-size: .76rem
        }

        .categories-form-body label {
            display: block;
            margin-bottom: 7px;
            color: #36383d;
            font-size: .84rem;
            font-weight: 700
        }

        .categories-form-body label span {
            color: #b30000
        }

        .categories-form-body input[type=text],
        .categories-form-body textarea {
            width: 100%;
            margin-bottom: 18px;
            padding: 12px 14px;
            border: 1px solid #dfe1e5;
            border-radius: 11px;
            color: #36383d;
            font-size: .88rem;
            line-height: 1.55;
            outline: none
        }

        .categories-form-body textarea {
            margin-bottom: 0;
            resize: vertical
        }

        .categories-form-body input[type=text]:focus,
        .categories-form-body textarea:focus {
            border-color: #b30000;
            box-shadow: 0 0 0 3px rgba(179, 0, 0, .08)
        }

        .categories-form-help {
            display: block;
            margin-top: 7px;
            color: #92959c;
            font-size: .75rem
        }

        .categories-confirm-body {
            overflow: auto;
            padding: 29px;
            text-align: center
        }

        .categories-confirm-body>span {
            display: grid;
            width: 65px;
            height: 65px;
            margin: 0 auto 14px;
            place-items: center;
            border-radius: 18px;
            font-size: 1.65rem
        }

        .categories-confirm-body>span.danger {
            background: #fff0f0;
            color: #b30000
        }

        .categories-confirm-body h3 {
            margin: 0 0 7px;
            font-size: 1.2rem
        }

        .categories-confirm-body p {
            max-width: 440px;
            margin: 0 auto;
            color: #71757d;
            font-size: .88rem;
            line-height: 1.6
        }

        .categories-confirm-body>div {
            display: flex;
            align-items: flex-start;
            gap: 8px;
            margin-top: 19px;
            padding: 12px 14px;
            border-radius: 10px;
            background: #f5f6f7;
            color: #666a71;
            font-size: .8rem;
            text-align: left
        }

        .categories-confirm-body>div.warning {
            background: #fff8ec;
            color: #8a6512
        }

        .categories-overlay-footer {
            display: flex;
            flex-shrink: 0;
            align-items: center;
            justify-content: flex-end;
            gap: 9px;
            padding: 14px 20px;
            border-top: 1px solid #e7e8eb;
            background: #fff
        }

        .categories-overlay-footer button {
            display: inline-flex;
            min-height: 39px;
            align-items: center;
            justify-content: center;
            gap: 7px;
            padding: 8px 14px;
            border: 0;
            border-radius: 9px;
            font-size: .82rem;
            font-weight: 700
        }

        .categories-overlay-footer .secondary {
            border: 1px solid #dedfe2;
            background: #fff;
            color: #666970
        }

        .categories-overlay-footer .primary {
            background: #b30000;
            color: #fff
        }

        .categories-overlay-footer .primary:hover {
            background: #8f0000
        }

        .categories-overlay-footer .danger {
            background: #b30000;
            color: #fff
        }

        @media(max-width:1050px) {
            .categories-stats {
                grid-template-columns: repeat(2, 1fr)
            }
        }

        @media(max-width:767px) {
            .categories-heading {
                align-items: flex-start;
                flex-direction: column
            }

            .categories-add {
                width: 100%;
                justify-content: center
            }

            .categories-toolbar {
                align-items: stretch;
                flex-direction: column
            }

            .categories-search {
                width: 100%;
                max-width: none
            }

            .categories-filter-btn {
                width: 100%
            }

            .categories-reset {
                justify-content: center
            }

            .categories-overlay {
                padding: 10px
            }

            .categories-overlay-footer {
                flex-wrap: wrap
            }

            .categories-overlay-footer button {
                flex: 1
            }

            .categories-overlay-footer form {
                flex: 1;
                display: flex
            }

            .categories-overlay-footer form button {
                width: 100%
            }

            .categories-pagination {
                align-items: flex-start;
                flex-direction: column
            }
        }

        @media(max-width:520px) {
            .categories-stats {
                grid-template-columns: 1fr
            }
        }
    
.categories-form-body input[type=number] {
    width: 100%;
    margin-bottom: 18px;
    padding: 12px 14px;
    border: 1px solid #dfe1e5;
    border-radius: 11px;
    color: #36383d;
    font-size: .88rem;
    outline: none;
}
.categories-form-body input[type=number]:focus {
    border-color: #b30000;
    box-shadow: 0 0 0 3px rgba(179, 0, 0, .08);
}
.categories-overlay-footer .confirm,
.categories-overlay-footer button[type=submit].confirm {
    background: #b30000;
    color: #fff;
}
.categories-overlay-footer .confirm:hover {
    background: #8f0000;
}
a.categories-add {
    text-decoration: none;
}
.categories-row-actions form {
    display: inline-flex;
    margin: 0;
}
.categories-toolbar .form-select {
    height: 42px;
    min-width: 160px;
    border: 1px solid #dfe1e5;
    border-radius: 10px;
    background: #fff;
    font-size: .85rem;
}

.categories-overlay-footer > button:not(.confirm):not(.primary):not(.danger):not(.secondary) {
    border: 1px solid #dedfe2;
    background: #fff;
    color: #666970;
}
.categories-stats article > div {
    min-width: 0;
}
</style>
