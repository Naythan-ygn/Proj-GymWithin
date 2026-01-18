{{--
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Styles -->
    <style>
        /*! tailwindcss v4.0.14 | MIT License | https://tailwindcss.com */
        @layer theme {

            :root,
            :host {
                --font-sans: ui-sans-serif, system-ui, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
                --font-mono: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
                --color-green-600: oklch(.627 .194 149.214);
                --color-gray-900: oklch(.21 .034 264.665);
                --color-zinc-50: oklch(.985 0 0);
                --color-zinc-200: oklch(.92 .004 286.32);
                --color-zinc-400: oklch(.705 .015 286.067);
                --color-zinc-500: oklch(.552 .016 285.938);
                --color-zinc-600: oklch(.442 .017 285.786);
                --color-zinc-700: oklch(.37 .013 285.805);
                --color-zinc-800: oklch(.274 .006 286.033);
                --color-zinc-900: oklch(.21 .006 285.885);
                --color-neutral-100: oklch(.97 0 0);
                --color-neutral-200: oklch(.922 0 0);
                --color-neutral-700: oklch(.371 0 0);
                --color-neutral-800: oklch(.269 0 0);
                --color-neutral-900: oklch(.205 0 0);
                --color-neutral-950: oklch(.145 0 0);
                --color-stone-800: oklch(.268 .007 34.298);
                --color-stone-950: oklch(.147 .004 49.25);
                --color-black: #000;
                --color-white: #fff;
                --spacing: .25rem;
                --container-sm: 24rem;
                --container-md: 28rem;
                --container-lg: 32rem;
                --container-4xl: 56rem;
                --text-xs: .75rem;
                --text-xs--line-height: calc(1/.75);
                --text-sm: .875rem;
                --text-sm--line-height: calc(1.25/.875);
                --text-lg: 1.125rem;
                --text-lg--line-height: calc(1.75/1.125);
                --font-weight-normal: 400;
                --font-weight-medium: 500;
                --font-weight-semibold: 600;
                --leading-tight: 1.25;
                --leading-normal: 1.5;
                --radius-sm: .25rem;
                --radius-md: .375rem;
                --radius-lg: .5rem;
                --radius-xl: .75rem;
                --aspect-video: 16/9;
                --default-transition-duration: .15s;
                --default-transition-timing-function: cubic-bezier(.4, 0, .2, 1);
                --default-font-family: var(--font-sans);
                --default-font-feature-settings: var(--font-sans--font-feature-settings);
                --default-font-variation-settings: var(--font-sans--font-variation-settings);
                --default-mono-font-family: var(--font-mono);
                --default-mono-font-feature-settings: var(--font-mono--font-feature-settings);
                --default-mono-font-variation-settings: var(--font-mono--font-variation-settings)
            }
        }

        @layer base {

            *,
            :after,
            :before,
            ::backdrop {
                box-sizing: border-box;
                border: 0 solid;
                margin: 0;
                padding: 0
            }

            ::file-selector-button {
                box-sizing: border-box;
                border: 0 solid;
                margin: 0;
                padding: 0
            }

            html,
            :host {
                -webkit-text-size-adjust: 100%;
                tab-size: 4;
                line-height: 1.5;
                font-family: var(--default-font-family, ui-sans-serif, system-ui, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji");
                font-feature-settings: var(--default-font-feature-settings, normal);
                font-variation-settings: var(--default-font-variation-settings, normal);
                -webkit-tap-highlight-color: transparent
            }

            body {
                line-height: inherit
            }

            hr {
                height: 0;
                color: inherit;
                border-top-width: 1px
            }

            abbr:where([title]) {
                -webkit-text-decoration: underline dotted;
                text-decoration: underline dotted
            }

            h1,
            h2,
            h3,
            h4,
            h5,
            h6 {
                font-size: inherit;
                font-weight: inherit
            }

            a {
                color: inherit;
                -webkit-text-decoration: inherit;
                -webkit-text-decoration: inherit;
                -webkit-text-decoration: inherit;
                text-decoration: inherit
            }

            b,
            strong {
                font-weight: bolder
            }

            code,
            kbd,
            samp,
            pre {
                font-family: var(--default-mono-font-family, ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace);
                font-feature-settings: var(--default-mono-font-feature-settings, normal);
                font-variation-settings: var(--default-mono-font-variation-settings, normal);
                font-size: 1em
            }

            small {
                font-size: 80%
            }

            sub,
            sup {
                vertical-align: baseline;
                font-size: 75%;
                line-height: 0;
                position: relative
            }

            sub {
                bottom: -.25em
            }

            sup {
                top: -.5em
            }

            table {
                text-indent: 0;
                border-color: inherit;
                border-collapse: collapse
            }

            :-moz-focusring {
                outline: auto
            }

            progress {
                vertical-align: baseline
            }

            summary {
                display: list-item
            }

            ol,
            ul,
            menu {
                list-style: none
            }

            img,
            svg,
            video,
            canvas,
            audio,
            iframe,
            embed,
            object {
                vertical-align: middle;
                display: block
            }

            img,
            video {
                max-width: 100%;
                height: auto
            }

            button,
            input,
            select,
            optgroup,
            textarea {
                font: inherit;
                font-feature-settings: inherit;
                font-variation-settings: inherit;
                letter-spacing: inherit;
                color: inherit;
                opacity: 1;
                background-color: #0000;
                border-radius: 0
            }

            ::file-selector-button {
                font: inherit;
                font-feature-settings: inherit;
                font-variation-settings: inherit;
                letter-spacing: inherit;
                color: inherit;
                opacity: 1;
                background-color: #0000;
                border-radius: 0
            }

            :where(select:is([multiple], [size])) optgroup {
                font-weight: bolder
            }

            :where(select:is([multiple], [size])) optgroup option {
                padding-inline-start: 20px
            }

            ::file-selector-button {
                margin-inline-end: 4px
            }

            ::placeholder {
                opacity: 1;
                color: color-mix(in oklab, currentColor 50%, transparent)
            }

            textarea {
                resize: vertical
            }

            ::-webkit-search-decoration {
                -webkit-appearance: none
            }

            ::-webkit-date-and-time-value {
                min-height: 1lh;
                text-align: inherit
            }

            ::-webkit-datetime-edit {
                display: inline-flex
            }

            ::-webkit-datetime-edit-fields-wrapper {
                padding: 0
            }

            ::-webkit-datetime-edit {
                padding-block: 0
            }

            ::-webkit-datetime-edit-year-field {
                padding-block: 0
            }

            ::-webkit-datetime-edit-month-field {
                padding-block: 0
            }

            ::-webkit-datetime-edit-day-field {
                padding-block: 0
            }

            ::-webkit-datetime-edit-hour-field {
                padding-block: 0
            }

            ::-webkit-datetime-edit-minute-field {
                padding-block: 0
            }

            ::-webkit-datetime-edit-second-field {
                padding-block: 0
            }

            ::-webkit-datetime-edit-millisecond-field {
                padding-block: 0
            }

            ::-webkit-datetime-edit-meridiem-field {
                padding-block: 0
            }

            :-moz-ui-invalid {
                box-shadow: none
            }

            button,
            input:where([type=button], [type=reset], [type=submit]) {
                appearance: button
            }

            ::file-selector-button {
                appearance: button
            }

            ::-webkit-inner-spin-button {
                height: auto
            }

            ::-webkit-outer-spin-button {
                height: auto
            }

            [hidden]:where(:not([hidden=until-found])) {
                display: none !important
            }
        }

        @layer components;

        @layer utilities {
            .sr-only {
                clip: rect(0, 0, 0, 0);
                white-space: nowrap;
                border-width: 0;
                width: 1px;
                height: 1px;
                margin: -1px;
                padding: 0;
                position: absolute;
                overflow: hidden
            }

            .absolute {
                position: absolute
            }

            .relative {
                position: relative
            }

            .static {
                position: static
            }

            .sticky {
                position: sticky
            }

            .inset-0 {
                inset: calc(var(--spacing)*0)
            }

            .inset-y-\[3px\] {
                inset-block: 3px
            }

            .start-0 {
                inset-inline-start: calc(var(--spacing)*0)
            }

            .end-0 {
                inset-inline-end: calc(var(--spacing)*0)
            }

            .top-0 {
                top: calc(var(--spacing)*0)
            }

            .z-20 {
                z-index: 20
            }

            .container {
                width: 100%
            }

            @media (width>=40rem) {
                .container {
                    max-width: 40rem
                }
            }

            @media (width>=48rem) {
                .container {
                    max-width: 48rem
                }
            }

            @media (width>=64rem) {
                .container {
                    max-width: 64rem
                }
            }

            @media (width>=80rem) {
                .container {
                    max-width: 80rem
                }
            }

            @media (width>=96rem) {
                .container {
                    max-width: 96rem
                }
            }

            .mx-auto {
                margin-inline: auto
            }

            .my-6 {
                margin-block: calc(var(--spacing)*6)
            }

            .-ms-8 {
                margin-inline-start: calc(var(--spacing)*-8)
            }

            .ms-1 {
                margin-inline-start: calc(var(--spacing)*1)
            }

            .ms-2 {
                margin-inline-start: calc(var(--spacing)*2)
            }

            .ms-4 {
                margin-inline-start: calc(var(--spacing)*4)
            }

            .me-1\.5 {
                margin-inline-end: calc(var(--spacing)*1.5)
            }

            .me-2 {
                margin-inline-end: calc(var(--spacing)*2)
            }

            .me-3 {
                margin-inline-end: calc(var(--spacing)*3)
            }

            .me-5 {
                margin-inline-end: calc(var(--spacing)*5)
            }

            .me-10 {
                margin-inline-end: calc(var(--spacing)*10)
            }

            .-mt-\[4\.9rem\] {
                margin-top: -4.9rem
            }

            .mt-2 {
                margin-top: calc(var(--spacing)*2)
            }

            .mt-4 {
                margin-top: calc(var(--spacing)*4)
            }

            .mt-5 {
                margin-top: calc(var(--spacing)*5)
            }

            .mt-6 {
                margin-top: calc(var(--spacing)*6)
            }

            .mt-10 {
                margin-top: calc(var(--spacing)*10)
            }

            .mt-auto {
                margin-top: auto
            }

            .-mb-px {
                margin-bottom: -1px
            }

            .mb-0\.5 {
                margin-bottom: calc(var(--spacing)*.5)
            }

            .mb-1 {
                margin-bottom: calc(var(--spacing)*1)
            }

            .mb-2 {
                margin-bottom: calc(var(--spacing)*2)
            }

            .mb-4 {
                margin-bottom: calc(var(--spacing)*4)
            }

            .mb-5 {
                margin-bottom: calc(var(--spacing)*5)
            }

            .mb-6 {
                margin-bottom: calc(var(--spacing)*6)
            }

            .mb-\[2px\] {
                margin-bottom: 2px
            }

            .block {
                display: block
            }

            .contents {
                display: contents
            }

            .flex {
                display: flex
            }

            .grid {
                display: grid
            }

            .hidden {
                display: none
            }

            .inline-block {
                display: inline-block
            }

            .inline-flex {
                display: inline-flex
            }

            .table {
                display: table
            }

            .aspect-\[335\/376\] {
                aspect-ratio: 335/376
            }

            .aspect-square {
                aspect-ratio: 1
            }

            .aspect-video {
                aspect-ratio: var(--aspect-video)
            }

            .size-3\! {
                width: calc(var(--spacing)*3) !important;
                height: calc(var(--spacing)*3) !important
            }

            .size-5 {
                width: calc(var(--spacing)*5);
                height: calc(var(--spacing)*5)
            }

            .size-8 {
                width: calc(var(--spacing)*8);
                height: calc(var(--spacing)*8)
            }

            .size-9 {
                width: calc(var(--spacing)*9);
                height: calc(var(--spacing)*9)
            }

            .size-full {
                width: 100%;
                height: 100%
            }

            .\!h-10 {
                height: calc(var(--spacing)*10) !important
            }

            .h-1\.5 {
                height: calc(var(--spacing)*1.5)
            }

            .h-2\.5 {
                height: calc(var(--spacing)*2.5)
            }

            .h-3\.5 {
                height: calc(var(--spacing)*3.5)
            }

            .h-7 {
                height: calc(var(--spacing)*7)
            }

            .h-8 {
                height: calc(var(--spacing)*8)
            }

            .h-9 {
                height: calc(var(--spacing)*9)
            }

            .h-10 {
                height: calc(var(--spacing)*10)
            }

            .h-14\.5 {
                height: calc(var(--spacing)*14.5)
            }

            .h-dvh {
                height: 100dvh
            }

            .h-full {
                height: 100%
            }

            .min-h-screen {
                min-height: 100vh
            }

            .min-h-svh {
                min-height: 100svh
            }

            .w-1\.5 {
                width: calc(var(--spacing)*1.5)
            }

            .w-2\.5 {
                width: calc(var(--spacing)*2.5)
            }

            .w-3\.5 {
                width: calc(var(--spacing)*3.5)
            }

            .w-8 {
                width: calc(var(--spacing)*8)
            }

            .w-9 {
                width: calc(var(--spacing)*9)
            }

            .w-10 {
                width: calc(var(--spacing)*10)
            }

            .w-\[220px\] {
                width: 220px
            }

            .w-\[448px\] {
                width: 448px
            }

            .w-full {
                width: 100%
            }

            .w-px {
                width: 1px
            }

            .max-w-\[335px\] {
                max-width: 335px
            }

            .max-w-lg {
                max-width: var(--container-lg)
            }

            .max-w-md {
                max-width: var(--container-md)
            }

            .max-w-none {
                max-width: none
            }

            .max-w-sm {
                max-width: var(--container-sm)
            }

            .flex-1 {
                flex: 1
            }

            .shrink-0 {
                flex-shrink: 0
            }

            .translate-y-0 {
                --tw-translate-y: calc(var(--spacing)*0);
                translate: var(--tw-translate-x)var(--tw-translate-y)
            }

            .cursor-pointer {
                cursor: pointer
            }

            .auto-rows-min {
                grid-auto-rows: min-content
            }

            .flex-col {
                flex-direction: column
            }

            .flex-col-reverse {
                flex-direction: column-reverse
            }

            .items-center {
                align-items: center
            }

            .items-start {
                align-items: flex-start
            }

            .justify-between {
                justify-content: space-between
            }

            .justify-center {
                justify-content: center
            }

            .justify-end {
                justify-content: flex-end
            }

            .gap-2 {
                gap: calc(var(--spacing)*2)
            }

            .gap-3 {
                gap: calc(var(--spacing)*3)
            }

            .gap-4 {
                gap: calc(var(--spacing)*4)
            }

            .gap-6 {
                gap: calc(var(--spacing)*6)
            }

            :where(.space-y-2>:not(:last-child)) {
                --tw-space-y-reverse: 0;
                margin-block-start: calc(calc(var(--spacing)*2)*var(--tw-space-y-reverse));
                margin-block-end: calc(calc(var(--spacing)*2)*calc(1 - var(--tw-space-y-reverse)))
            }

            :where(.space-y-3>:not(:last-child)) {
                --tw-space-y-reverse: 0;
                margin-block-start: calc(calc(var(--spacing)*3)*var(--tw-space-y-reverse));
                margin-block-end: calc(calc(var(--spacing)*3)*calc(1 - var(--tw-space-y-reverse)))
            }

            :where(.space-y-6>:not(:last-child)) {
                --tw-space-y-reverse: 0;
                margin-block-start: calc(calc(var(--spacing)*6)*var(--tw-space-y-reverse));
                margin-block-end: calc(calc(var(--spacing)*6)*calc(1 - var(--tw-space-y-reverse)))
            }

            :where(.space-y-\[2px\]>:not(:last-child)) {
                --tw-space-y-reverse: 0;
                margin-block-start: calc(2px*var(--tw-space-y-reverse));
                margin-block-end: calc(2px*calc(1 - var(--tw-space-y-reverse)))
            }

            :where(.space-x-0\.5>:not(:last-child)) {
                --tw-space-x-reverse: 0;
                margin-inline-start: calc(calc(var(--spacing)*.5)*var(--tw-space-x-reverse));
                margin-inline-end: calc(calc(var(--spacing)*.5)*calc(1 - var(--tw-space-x-reverse)))
            }

            :where(.space-x-1>:not(:last-child)) {
                --tw-space-x-reverse: 0;
                margin-inline-start: calc(calc(var(--spacing)*1)*var(--tw-space-x-reverse));
                margin-inline-end: calc(calc(var(--spacing)*1)*calc(1 - var(--tw-space-x-reverse)))
            }

            :where(.space-x-2>:not(:last-child)) {
                --tw-space-x-reverse: 0;
                margin-inline-start: calc(calc(var(--spacing)*2)*var(--tw-space-x-reverse));
                margin-inline-end: calc(calc(var(--spacing)*2)*calc(1 - var(--tw-space-x-reverse)))
            }

            .self-stretch {
                align-self: stretch
            }

            .truncate {
                text-overflow: ellipsis;
                white-space: nowrap;
                overflow: hidden
            }

            .overflow-hidden {
                overflow: hidden
            }

            .rounded-full {
                border-radius: 3.40282e38px
            }

            .rounded-lg {
                border-radius: var(--radius-lg)
            }

            .rounded-md {
                border-radius: var(--radius-md)
            }

            .rounded-sm {
                border-radius: var(--radius-sm)
            }

            .rounded-xl {
                border-radius: var(--radius-xl)
            }

            .rounded-ee-lg {
                border-end-end-radius: var(--radius-lg)
            }

            .rounded-es-lg {
                border-end-start-radius: var(--radius-lg)
            }

            .rounded-t-lg {
                border-top-left-radius: var(--radius-lg);
                border-top-right-radius: var(--radius-lg)
            }

            .border {
                border-style: var(--tw-border-style);
                border-width: 1px
            }

            .border-r {
                border-right-style: var(--tw-border-style);
                border-right-width: 1px
            }

            .border-b {
                border-bottom-style: var(--tw-border-style);
                border-bottom-width: 1px
            }

            .border-\[\#19140035\] {
                border-color: #19140035
            }

            .border-\[\#e3e3e0\] {
                border-color: #e3e3e0
            }

            .border-black {
                border-color: var(--color-black)
            }

            .border-neutral-200 {
                border-color: var(--color-neutral-200)
            }

            .border-transparent {
                border-color: #0000
            }

            .border-zinc-200 {
                border-color: var(--color-zinc-200)
            }

            .bg-\[\#1b1b18\] {
                background-color: #1b1b18
            }

            .bg-\[\#FDFDFC\] {
                background-color: #fdfdfc
            }

            .bg-\[\#dbdbd7\] {
                background-color: #dbdbd7
            }

            .bg-\[\#fff2f2\] {
                background-color: #fff2f2
            }

            .bg-neutral-100 {
                background-color: var(--color-neutral-100)
            }

            .bg-neutral-200 {
                background-color: var(--color-neutral-200)
            }

            .bg-neutral-900 {
                background-color: var(--color-neutral-900)
            }

            .bg-white {
                background-color: var(--color-white)
            }

            .bg-zinc-50 {
                background-color: var(--color-zinc-50)
            }

            .bg-zinc-200 {
                background-color: var(--color-zinc-200)
            }

            .fill-current {
                fill: currentColor
            }

            .stroke-gray-900\/20 {
                stroke: color-mix(in oklab, var(--color-gray-900)20%, transparent)
            }

            .p-0 {
                padding: calc(var(--spacing)*0)
            }

            .p-6 {
                padding: calc(var(--spacing)*6)
            }

            .p-10 {
                padding: calc(var(--spacing)*10)
            }

            .px-1 {
                padding-inline: calc(var(--spacing)*1)
            }

            .px-5 {
                padding-inline: calc(var(--spacing)*5)
            }

            .px-8 {
                padding-inline: calc(var(--spacing)*8)
            }

            .px-10 {
                padding-inline: calc(var(--spacing)*10)
            }

            .py-0\! {
                padding-block: calc(var(--spacing)*0) !important
            }

            .py-1 {
                padding-block: calc(var(--spacing)*1)
            }

            .py-1\.5 {
                padding-block: calc(var(--spacing)*1.5)
            }

            .py-2 {
                padding-block: calc(var(--spacing)*2)
            }

            .py-8 {
                padding-block: calc(var(--spacing)*8)
            }

            .ps-3 {
                padding-inline-start: calc(var(--spacing)*3)
            }

            .ps-7 {
                padding-inline-start: calc(var(--spacing)*7)
            }

            .pe-4 {
                padding-inline-end: calc(var(--spacing)*4)
            }

            .pb-4 {
                padding-bottom: calc(var(--spacing)*4)
            }

            .pb-12 {
                padding-bottom: calc(var(--spacing)*12)
            }

            .text-center {
                text-align: center
            }

            .text-start {
                text-align: start
            }

            .text-lg {
                font-size: var(--text-lg);
                line-height: var(--tw-leading, var(--text-lg--line-height))
            }

            .text-sm {
                font-size: var(--text-sm);
                line-height: var(--tw-leading, var(--text-sm--line-height))
            }

            .text-xs {
                font-size: var(--text-xs);
                line-height: var(--tw-leading, var(--text-xs--line-height))
            }

            .text-\[13px\] {
                font-size: 13px
            }

            .leading-\[20px\] {
                --tw-leading: 20px;
                line-height: 20px
            }

            .leading-none {
                --tw-leading: 1;
                line-height: 1
            }

            .leading-normal {
                --tw-leading: var(--leading-normal);
                line-height: var(--leading-normal)
            }

            .leading-tight {
                --tw-leading: var(--leading-tight);
                line-height: var(--leading-tight)
            }

            .font-medium {
                --tw-font-weight: var(--font-weight-medium);
                font-weight: var(--font-weight-medium)
            }

            .font-normal {
                --tw-font-weight: var(--font-weight-normal);
                font-weight: var(--font-weight-normal)
            }

            .font-semibold {
                --tw-font-weight: var(--font-weight-semibold);
                font-weight: var(--font-weight-semibold)
            }

            .\!text-green-600 {
                color: var(--color-green-600) !important
            }

            .text-\[\#1b1b18\] {
                color: #1b1b18
            }

            .text-\[\#706f6c\] {
                color: #706f6c
            }

            .text-\[\#F53003\],
            .text-\[\#f53003\] {
                color: #f53003
            }

            .text-black {
                color: var(--color-black)
            }

            .text-green-600 {
                color: var(--color-green-600)
            }

            .text-stone-800 {
                color: var(--color-stone-800)
            }

            .text-white {
                color: var(--color-white)
            }

            .text-zinc-400 {
                color: var(--color-zinc-400)
            }

            .text-zinc-500 {
                color: var(--color-zinc-500)
            }

            .text-zinc-600 {
                color: var(--color-zinc-600)
            }

            .lowercase {
                text-transform: lowercase
            }

            .underline {
                text-decoration-line: underline
            }

            .underline-offset-4 {
                text-underline-offset: 4px
            }

            .antialiased {
                -webkit-font-smoothing: antialiased;
                -moz-osx-font-smoothing: grayscale
            }

            .opacity-100 {
                opacity: 1
            }

            .shadow-\[0px_0px_1px_0px_rgba\(0\,0\,0\,0\.03\)\,0px_1px_2px_0px_rgba\(0\,0\,0\,0\.06\)\] {
                --tw-shadow: 0px 0px 1px 0px var(--tw-shadow-color, #00000008), 0px 1px 2px 0px var(--tw-shadow-color, #0000000f);
                box-shadow: var(--tw-inset-shadow), var(--tw-inset-ring-shadow), var(--tw-ring-offset-shadow), var(--tw-ring-shadow), var(--tw-shadow)
            }

            .shadow-\[inset_0px_0px_0px_1px_rgba\(26\,26\,0\,0\.16\)\] {
                --tw-shadow: inset 0px 0px 0px 1px var(--tw-shadow-color, #1a1a0029);
                box-shadow: var(--tw-inset-shadow), var(--tw-inset-ring-shadow), var(--tw-ring-offset-shadow), var(--tw-ring-shadow), var(--tw-shadow)
            }

            .shadow-xs {
                --tw-shadow: 0 1px 2px 0 var(--tw-shadow-color, #0000000d);
                box-shadow: var(--tw-inset-shadow), var(--tw-inset-ring-shadow), var(--tw-ring-offset-shadow), var(--tw-ring-shadow), var(--tw-shadow)
            }

            .outline {
                outline-style: var(--tw-outline-style);
                outline-width: 1px
            }

            .transition-all {
                transition-property: all;
                transition-timing-function: var(--tw-ease, var(--default-transition-timing-function));
                transition-duration: var(--tw-duration, var(--default-transition-duration))
            }

            .transition-opacity {
                transition-property: opacity;
                transition-timing-function: var(--tw-ease, var(--default-transition-timing-function));
                transition-duration: var(--tw-duration, var(--default-transition-duration))
            }

            .delay-300 {
                transition-delay: .3s
            }

            .duration-750 {
                --tw-duration: .75s;
                transition-duration: .75s
            }

            .not-has-\[nav\]\:hidden:not(:has(:is(nav))) {
                display: none
            }

            .group-data-open\/disclosure-button\:block:is(:where(.group\/disclosure-button)[data-open] *) {
                display: block
            }

            .group-data-open\/disclosure-button\:hidden:is(:where(.group\/disclosure-button)[data-open] *) {
                display: none
            }

            .before\:absolute:before {
                content: var(--tw-content);
                position: absolute
            }

            .before\:start-\[0\.4rem\]:before {
                content: var(--tw-content);
                inset-inline-start: .4rem
            }

            .before\:top-0:before {
                content: var(--tw-content);
                top: calc(var(--spacing)*0)
            }

            .before\:top-1\/2:before {
                content: var(--tw-content);
                top: 50%
            }

            .before\:bottom-0:before {
                content: var(--tw-content);
                bottom: calc(var(--spacing)*0)
            }

            .before\:bottom-1\/2:before {
                content: var(--tw-content);
                bottom: 50%
            }

            .before\:left-\[0\.4rem\]:before {
                content: var(--tw-content);
                left: .4rem
            }

            .before\:border-l:before {
                content: var(--tw-content);
                border-left-style: var(--tw-border-style);
                border-left-width: 1px
            }

            .before\:border-\[\#e3e3e0\]:before {
                content: var(--tw-content);
                border-color: #e3e3e0
            }

            @media (hover:hover) {
                .hover\:border-\[\#1915014a\]:hover {
                    border-color: #1915014a
                }

                .hover\:border-\[\#19140035\]:hover {
                    border-color: #19140035
                }

                .hover\:border-black:hover {
                    border-color: var(--color-black)
                }

                .hover\:bg-black:hover {
                    background-color: var(--color-black)
                }

                .hover\:bg-zinc-800\/5:hover {
                    background-color: color-mix(in oklab, var(--color-zinc-800)5%, transparent)
                }

                .hover\:text-zinc-800:hover {
                    color: var(--color-zinc-800)
                }
            }

            .data-open\:block[data-open] {
                display: block
            }

            @media (width<64rem) {
                .max-lg\:hidden {
                    display: none
                }
            }

            @media (width<48rem) {
                .max-md\:flex-col {
                    flex-direction: column
                }

                .max-md\:pt-6 {
                    padding-top: calc(var(--spacing)*6)
                }
            }

            @media (width>=40rem) {
                .sm\:w-\[350px\] {
                    width: 350px
                }

                .sm\:px-0 {
                    padding-inline: calc(var(--spacing)*0)
                }
            }

            @media (width>=48rem) {
                .md\:hidden {
                    display: none
                }

                .md\:w-\[220px\] {
                    width: 220px
                }

                .md\:grid-cols-3 {
                    grid-template-columns: repeat(3, minmax(0, 1fr))
                }

                .md\:p-10 {
                    padding: calc(var(--spacing)*10)
                }
            }

            @media (width>=64rem) {
                .lg\:-ms-px {
                    margin-inline-start: -1px
                }

                .lg\:ms-0 {
                    margin-inline-start: calc(var(--spacing)*0)
                }

                .lg\:-mt-\[6\.6rem\] {
                    margin-top: -6.6rem
                }

                .lg\:mb-0 {
                    margin-bottom: calc(var(--spacing)*0)
                }

                .lg\:mb-6 {
                    margin-bottom: calc(var(--spacing)*6)
                }

                .lg\:block {
                    display: block
                }

                .lg\:flex {
                    display: flex
                }

                .lg\:hidden {
                    display: none
                }

                .lg\:aspect-auto {
                    aspect-ratio: auto
                }

                .lg\:h-8 {
                    height: calc(var(--spacing)*8)
                }

                .lg\:w-\[438px\] {
                    width: 438px
                }

                .lg\:max-w-4xl {
                    max-width: var(--container-4xl)
                }

                .lg\:max-w-none {
                    max-width: none
                }

                .lg\:grow {
                    flex-grow: 1
                }

                .lg\:grid-cols-2 {
                    grid-template-columns: repeat(2, minmax(0, 1fr))
                }

                .lg\:flex-row {
                    flex-direction: row
                }

                .lg\:justify-center {
                    justify-content: center
                }

                .lg\:rounded-ss-lg {
                    border-start-start-radius: var(--radius-lg)
                }

                .lg\:rounded-e-lg {
                    border-start-end-radius: var(--radius-lg);
                    border-end-end-radius: var(--radius-lg)
                }

                .lg\:rounded-e-lg\! {
                    border-start-end-radius: var(--radius-lg) !important;
                    border-end-end-radius: var(--radius-lg) !important
                }

                .lg\:rounded-ee-none {
                    border-end-end-radius: 0
                }

                .lg\:rounded-t-none {
                    border-top-left-radius: 0;
                    border-top-right-radius: 0
                }

                .lg\:p-8 {
                    padding: calc(var(--spacing)*8)
                }

                .lg\:p-20 {
                    padding: calc(var(--spacing)*20)
                }

                .lg\:px-0 {
                    padding-inline: calc(var(--spacing)*0)
                }
            }

            :where(.rtl\:space-x-reverse:where(:dir(rtl), [dir=rtl], [dir=rtl] *)>:not(:last-child)) {
                --tw-space-x-reverse: 1
            }

            @media (prefers-color-scheme:dark) {
                .dark\:block {
                    display: block
                }

                .dark\:hidden {
                    display: none
                }

                .dark\:border-r {
                    border-right-style: var(--tw-border-style);
                    border-right-width: 1px
                }

                .dark\:border-\[\#3E3E3A\] {
                    border-color: #3e3e3a
                }

                .dark\:border-\[\#eeeeec\] {
                    border-color: #eeeeec
                }

                .dark\:border-neutral-700 {
                    border-color: var(--color-neutral-700)
                }

                .dark\:border-neutral-800 {
                    border-color: var(--color-neutral-800)
                }

                .dark\:border-stone-800 {
                    border-color: var(--color-stone-800)
                }

                .dark\:border-zinc-700 {
                    border-color: var(--color-zinc-700)
                }

                .dark\:bg-\[\#0a0a0a\] {
                    background-color: #0a0a0a
                }

                .dark\:bg-\[\#1D0002\] {
                    background-color: #1d0002
                }

                .dark\:bg-\[\#3E3E3A\] {
                    background-color: #3e3e3a
                }

                .dark\:bg-\[\#161615\] {
                    background-color: #161615
                }

                .dark\:bg-\[\#eeeeec\] {
                    background-color: #eeeeec
                }

                .dark\:bg-neutral-700 {
                    background-color: var(--color-neutral-700)
                }

                .dark\:bg-stone-950 {
                    background-color: var(--color-stone-950)
                }

                .dark\:bg-white\/30 {
                    background-color: color-mix(in oklab, var(--color-white)30%, transparent)
                }

                .dark\:bg-zinc-800 {
                    background-color: var(--color-zinc-800)
                }

                .dark\:bg-zinc-900 {
                    background-color: var(--color-zinc-900)
                }

                .dark\:bg-linear-to-b {
                    --tw-gradient-position: to bottom in oklab;
                    background-image: linear-gradient(var(--tw-gradient-stops))
                }

                .dark\:from-neutral-950 {
                    --tw-gradient-from: var(--color-neutral-950);
                    --tw-gradient-stops: var(--tw-gradient-via-stops, var(--tw-gradient-position), var(--tw-gradient-from)var(--tw-gradient-from-position), var(--tw-gradient-to)var(--tw-gradient-to-position))
                }

                .dark\:to-neutral-900 {
                    --tw-gradient-to: var(--color-neutral-900);
                    --tw-gradient-stops: var(--tw-gradient-via-stops, var(--tw-gradient-position), var(--tw-gradient-from)var(--tw-gradient-from-position), var(--tw-gradient-to)var(--tw-gradient-to-position))
                }

                .dark\:stroke-neutral-100\/20 {
                    stroke: color-mix(in oklab, var(--color-neutral-100)20%, transparent)
                }

                .dark\:text-\[\#1C1C1A\] {
                    color: #1c1c1a
                }

                .dark\:text-\[\#A1A09A\] {
                    color: #a1a09a
                }

                .dark\:text-\[\#EDEDEC\] {
                    color: #ededec
                }

                .dark\:text-\[\#F61500\] {
                    color: #f61500
                }

                .dark\:text-\[\#FF4433\] {
                    color: #f43
                }

                .dark\:text-black {
                    color: var(--color-black)
                }

                .dark\:text-white {
                    color: var(--color-white)
                }

                .dark\:text-white\/80 {
                    color: color-mix(in oklab, var(--color-white)80%, transparent)
                }

                .dark\:text-zinc-400 {
                    color: var(--color-zinc-400)
                }

                .dark\:shadow-\[inset_0px_0px_0px_1px_\#fffaed2d\] {
                    --tw-shadow: inset 0px 0px 0px 1px var(--tw-shadow-color, #fffaed2d);
                    box-shadow: var(--tw-inset-shadow), var(--tw-inset-ring-shadow), var(--tw-ring-offset-shadow), var(--tw-ring-shadow), var(--tw-shadow)
                }

                .dark\:before\:border-\[\#3E3E3A\]:before {
                    content: var(--tw-content);
                    border-color: #3e3e3a
                }

                @media (hover:hover) {
                    .dark\:hover\:border-\[\#3E3E3A\]:hover {
                        border-color: #3e3e3a
                    }

                    .dark\:hover\:border-\[\#62605b\]:hover {
                        border-color: #62605b
                    }

                    .dark\:hover\:border-white:hover {
                        border-color: var(--color-white)
                    }

                    .dark\:hover\:bg-white:hover {
                        background-color: var(--color-white)
                    }

                    .dark\:hover\:bg-white\/\[7\%\]:hover {
                        background-color: color-mix(in oklab, var(--color-white)7%, transparent)
                    }

                    .dark\:hover\:text-white:hover {
                        color: var(--color-white)
                    }
                }
            }

            @starting-style {
                .starting\:translate-y-4 {
                    --tw-translate-y: calc(var(--spacing)*4);
                    translate: var(--tw-translate-x)var(--tw-translate-y)
                }
            }

            @starting-style {
                .starting\:translate-y-6 {
                    --tw-translate-y: calc(var(--spacing)*6);
                    translate: var(--tw-translate-x)var(--tw-translate-y)
                }
            }

            @starting-style {
                .starting\:opacity-0 {
                    opacity: 0
                }
            }

            .\[\&\>div\>svg\]\:size-5>div>svg {
                width: calc(var(--spacing)*5);
                height: calc(var(--spacing)*5)
            }

            :where(.\[\:where\(\&\)\]\:size-4) {
                width: calc(var(--spacing)*4);
                height: calc(var(--spacing)*4)
            }

            :where(.\[\:where\(\&\)\]\:size-5) {
                width: calc(var(--spacing)*5);
                height: calc(var(--spacing)*5)
            }

            :where(.\[\:where\(\&\)\]\:size-6) {
                width: calc(var(--spacing)*6);
                height: calc(var(--spacing)*6)
            }
        }

        @property --tw-translate-x {
            syntax: "*";
            inherits: false;
            initial-value: 0
        }

        @property --tw-translate-y {
            syntax: "*";
            inherits: false;
            initial-value: 0
        }

        @property --tw-translate-z {
            syntax: "*";
            inherits: false;
            initial-value: 0
        }

        @property --tw-space-y-reverse {
            syntax: "*";
            inherits: false;
            initial-value: 0
        }

        @property --tw-space-x-reverse {
            syntax: "*";
            inherits: false;
            initial-value: 0
        }

        @property --tw-border-style {
            syntax: "*";
            inherits: false;
            initial-value: solid
        }

        @property --tw-leading {
            syntax: "*";
            inherits: false
        }

        @property --tw-font-weight {
            syntax: "*";
            inherits: false
        }

        @property --tw-shadow {
            syntax: "*";
            inherits: false;
            initial-value: 0 0 #0000
        }

        @property --tw-shadow-color {
            syntax: "*";
            inherits: false
        }

        @property --tw-inset-shadow {
            syntax: "*";
            inherits: false;
            initial-value: 0 0 #0000
        }

        @property --tw-inset-shadow-color {
            syntax: "*";
            inherits: false
        }

        @property --tw-ring-color {
            syntax: "*";
            inherits: false
        }

        @property --tw-ring-shadow {
            syntax: "*";
            inherits: false;
            initial-value: 0 0 #0000
        }

        @property --tw-inset-ring-color {
            syntax: "*";
            inherits: false
        }

        @property --tw-inset-ring-shadow {
            syntax: "*";
            inherits: false;
            initial-value: 0 0 #0000
        }

        @property --tw-ring-inset {
            syntax: "*";
            inherits: false
        }

        @property --tw-ring-offset-width {
            syntax: "<length>";
            inherits: false;
            initial-value: 0
        }

        @property --tw-ring-offset-color {
            syntax: "*";
            inherits: false;
            initial-value: #fff
        }

        @property --tw-ring-offset-shadow {
            syntax: "*";
            inherits: false;
            initial-value: 0 0 #0000
        }

        @property --tw-outline-style {
            syntax: "*";
            inherits: false;
            initial-value: solid
        }

        @property --tw-duration {
            syntax: "*";
            inherits: false
        }

        @property --tw-content {
            syntax: "*";
            inherits: false;
            initial-value: ""
        }

        @property --tw-gradient-position {
            syntax: "*";
            inherits: false
        }

        @property --tw-gradient-from {
            syntax: "<color>";
            inherits: false;
            initial-value: #0000
        }

        @property --tw-gradient-via {
            syntax: "<color>";
            inherits: false;
            initial-value: #0000
        }

        @property --tw-gradient-to {
            syntax: "<color>";
            inherits: false;
            initial-value: #0000
        }

        @property --tw-gradient-stops {
            syntax: "*";
            inherits: false
        }

        @property --tw-gradient-via-stops {
            syntax: "*";
            inherits: false
        }

        @property --tw-gradient-from-position {
            syntax: "<length-percentage>";
            inherits: false;
            initial-value: 0%
        }

        @property --tw-gradient-via-position {
            syntax: "<length-percentage>";
            inherits: false;
            initial-value: 50%
        }

        @property --tw-gradient-to-position {
            syntax: "<length-percentage>";
            inherits: false;
            initial-value: 100%
        }
    </style>
</head>

<body
    class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-white flex p-6 lg:p-8 items-center lg:justify-center min-h-screen flex-col">
    <header class="w-full lg:max-w-4xl max-w-[335px] text-sm mb-6 not-has-[nav]:hidden">
        @if (Route::has('login'))
        <nav class="flex items-center justify-end gap-4">
            @auth
            <a href="{{ url('/dashboard') }}"
                class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-sm text-sm leading-normal">
                Dashboard
            </a>
            @else
            <a href="{{ route('login') }}"
                class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] text-[#1b1b18] border border-transparent hover:border-[#19140035] dark:hover:border-[#3E3E3A] rounded-sm text-sm leading-normal">
                Log in
            </a>

            @if (Route::has('register'))
            <a href="{{ route('register') }}"
                class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-sm text-sm leading-normal">
                Register
            </a>
            @endif
            @endauth
        </nav>
        @endif
    </header>
    <div
        class="flex items-center justify-center w-full transition-opacity opacity-100 duration-750 lg:grow starting:opacity-0">
        <main class="flex max-w-[335px] w-full flex-col-reverse lg:max-w-4xl lg:flex-row">

        </main>
    </div>

    @if (Route::has('login'))
    <div class="h-14.5 hidden lg:block"></div>
    @endif
</body>

</html> --}}

<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GymWithin - Premium Fitness Equipment</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/motion@11.11.13/dist/motion.js"></script>
    <link rel="stylesheet" href="{{ asset('css/welcome.css') }}">
</head>

<body class="bg-black text-white overflow-x-hidden">

    <!-- Premium Navigation -->
    <nav class="glass-nav fixed top-0 left-0 right-0 z-50">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex items-center space-x-2">
                    <div class="w-8 h-8 bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg"></div>
                    <span class="text-xl font-bold tracking-tight">GymWithin</span>
                </div>

                <div class="hidden md:flex items-center space-x-8 text-sm font-medium">
                    <a href="#equipment" class="text-gray-300 hover:text-white transition-colors">Equipment</a>
                    <a href="#benefits" class="text-gray-300 hover:text-white transition-colors">Benefits</a>
                    <a href="#cta" class="text-gray-300 hover:text-white transition-colors">Get Started</a>
                    <a href="#footer" class="text-gray-300 hover:text-white transition-colors">Contact</a>
                </div>

                <div class="flex items-center space-x-4">
                    @auth
                        <a href="{{ url('/dashboard') }}"
                            class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-sm text-sm leading-normal">
                            Dashboard
                        </a>
                    @else
                        {{-- class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] text-[#1b1b18] border border-transparent
                        hover:border-[#19140035] dark:hover:border-[#3E3E3A] rounded-sm text-sm leading-normal" --}}
                        <a href="{{ route('login') }}">
                            <button
                                class="hidden sm:block text-sm font-medium text-gray-300 hover:text-white transition-colors">

                                Log in
                            </button>
                        </a>
                        @if (Route::has('register'))
                            {{-- class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] hover:border-[#1915014a]
                            border text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-sm text-sm
                            leading-normal" --}}
                            <a href="{{ route('register') }}">
                                <button
                                    class="bg-white text-black px-5 py-2.5 rounded-full text-sm font-semibold hover:bg-gray-100 transition-all">
                                    Register
                                </button>
                            </a>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section with Motion.dev Scroll Animation -->
    <section class="hero-wrapper">
        <div class="hero-canvas">
            <img id="heroImage" src="{{ asset('Treadmill_Images/treadmill_hero.webp') }}" alt="Premium Treadmill"
                class="hero-image">

            <!-- Gradient Overlays -->
            <div
                class="absolute inset-0 bg-gradient-to-b from-black/40 via-transparent to-black/80 pointer-events-none">
            </div>
            <div
                class="absolute inset-0 bg-gradient-to-r from-black/30 via-transparent to-black/30 pointer-events-none">
            </div>

            <!-- Hero Content -->
            <div class="absolute inset-0 flex items-center justify-center">
                <div class="text-center px-6 max-w-5xl">
                    <h1
                        class="text-5xl sm:text-6xl md:text-7xl lg:text-8xl font-bold tracking-tight mb-6 leading-[1.1]">
                        <span class="block text-white">Redefine Your</span>
                        <span class="block gradient-text mt-2">Fitness Standards</span>
                    </h1>
                    <p class="text-lg sm:text-xl text-gray-300 mb-10 max-w-2xl mx-auto font-light">
                        Experience commercial-grade equipment designed for champions. Premium quality meets intelligent
                        design.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                        <button
                            class="magnetic-btn bg-gradient-to-r from-orange-500 to-orange-600 px-8 py-4 rounded-full font-semibold text-base w-full sm:w-auto">
                            Explore Equipment
                        </button>
                        <button
                            class="glass-card px-8 py-4 rounded-full font-semibold text-base hover:bg-white/10 transition-all w-full sm:w-auto">
                            Watch Demo
                        </button>
                    </div>
                </div>
            </div>

            <!-- Scroll Indicator -->
            <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 flex flex-col items-center gap-2">
                <span class="text-xs text-gray-400 uppercase tracking-widest">Scroll to explore</span>
                <div class="w-6 h-10 border-2 border-white/30 rounded-full flex justify-center p-1">
                    <div class="w-1.5 h-3 bg-white/60 rounded-full animate-bounce"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Equipment Section -->
    <section id="equipment" class="py-24 px-6 bg-black">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16 fade-in" data-fade>
                <h2 class="text-4xl md:text-5xl lg:text-6xl font-bold tracking-tight mb-4">
                    Premium <span class="gradient-text">Equipment</span>
                </h2>
                <p class="text-gray-400 text-lg max-w-2xl mx-auto font-light">
                    Engineered for performance. Built to inspire greatness.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Product Card 1 -->
                <div class="glass-card rounded-3xl overflow-hidden hover:border-orange-500/50 transition-all duration-500 group fade-in"
                    data-fade>
                    <div
                        class="aspect-square bg-gradient-to-br from-gray-900 to-black flex items-center justify-center relative overflow-hidden">
                        <div class="text-8xl group-hover:scale-110 transition-transform duration-500">🏃</div>
                        <div
                            class="absolute top-4 right-4 bg-orange-500 text-white px-3 py-1 rounded-full text-xs font-semibold">
                            FEATURED
                        </div>
                    </div>
                    <div class="p-8">
                        <h3 class="text-2xl font-bold mb-2 group-hover:text-orange-500 transition-colors">Pro Treadmill
                            X1</h3>
                        <p class="text-gray-400 mb-6 text-sm leading-relaxed">
                            Advanced shock absorption with AI-powered performance tracking
                        </p>
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-3xl font-bold text-white">$2,499</div>
                                <div class="text-xs text-gray-500">or $104/mo</div>
                            </div>
                            <button
                                class="bg-white text-black px-6 py-3 rounded-full font-semibold text-sm hover:bg-gray-200 transition-all">
                                Learn More
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Product Card 2 -->
                <div class="glass-card rounded-3xl overflow-hidden hover:border-orange-500/50 transition-all duration-500 group fade-in"
                    data-fade>
                    <div
                        class="aspect-square bg-gradient-to-br from-gray-900 to-black flex items-center justify-center relative overflow-hidden">
                        <div class="text-8xl group-hover:scale-110 transition-transform duration-500">💪</div>
                    </div>
                    <div class="p-8">
                        <h3 class="text-2xl font-bold mb-2 group-hover:text-orange-500 transition-colors">PowerRack
                            Elite</h3>
                        <p class="text-gray-400 mb-6 text-sm leading-relaxed">
                            Commercial-grade steel construction with unlimited exercise versatility
                        </p>
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-3xl font-bold text-white">$1,899</div>
                                <div class="text-xs text-gray-500">or $79/mo</div>
                            </div>
                            <button
                                class="bg-white text-black px-6 py-3 rounded-full font-semibold text-sm hover:bg-gray-200 transition-all">
                                Learn More
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Product Card 3 -->
                <div class="glass-card rounded-3xl overflow-hidden hover:border-orange-500/50 transition-all duration-500 group fade-in"
                    data-fade>
                    <div
                        class="aspect-square bg-gradient-to-br from-gray-900 to-black flex items-center justify-center relative overflow-hidden">
                        <div class="text-8xl group-hover:scale-110 transition-transform duration-500">🚴</div>
                    </div>
                    <div class="p-8">
                        <h3 class="text-2xl font-bold mb-2 group-hover:text-orange-500 transition-colors">Spin Cycle Pro
                        </h3>
                        <p class="text-gray-400 mb-6 text-sm leading-relaxed">
                            Studio-quality magnetic resistance with live performance metrics
                        </p>
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-3xl font-bold text-white">$1,299</div>
                                <div class="text-xs text-gray-500">or $54/mo</div>
                            </div>
                            <button
                                class="bg-white text-black px-6 py-3 rounded-full font-semibold text-sm hover:bg-gray-200 transition-all">
                                Learn More
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Benefits Section -->
    <section id="benefits" class="py-24 px-6 bg-gradient-to-b from-black to-gray-950">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16 fade-in" data-fade>
                <h2 class="text-4xl md:text-5xl lg:text-6xl font-bold tracking-tight mb-4">
                    Why <span class="gradient-text">GymWithin</span>
                </h2>
                <p class="text-gray-400 text-lg max-w-2xl mx-auto font-light">
                    More than equipment. A commitment to excellence.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="text-center fade-in" data-fade>
                    <div
                        class="w-16 h-16 bg-gradient-to-br from-orange-500/20 to-orange-600/20 rounded-2xl flex items-center justify-center mx-auto mb-6 border border-orange-500/30">
                        <span class="text-4xl">🏆</span>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Premium Quality</h3>
                    <p class="text-gray-400 text-sm leading-relaxed">
                        Commercial-grade materials engineered for a lifetime of performance
                    </p>
                </div>

                <div class="text-center fade-in" data-fade>
                    <div
                        class="w-16 h-16 bg-gradient-to-br from-orange-500/20 to-orange-600/20 rounded-2xl flex items-center justify-center mx-auto mb-6 border border-orange-500/30">
                        <span class="text-4xl">🚚</span>
                    </div>
                    <h3 class="text-xl font-bold mb-3">White Glove Service</h3>
                    <p class="text-gray-400 text-sm leading-relaxed">
                        Complimentary delivery and professional installation included
                    </p>
                </div>

                <div class="text-center fade-in" data-fade>
                    <div
                        class="w-16 h-16 bg-gradient-to-br from-orange-500/20 to-orange-600/20 rounded-2xl flex items-center justify-center mx-auto mb-6 border border-orange-500/30">
                        <span class="text-4xl">🛡️</span>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Lifetime Warranty</h3>
                    <p class="text-gray-400 text-sm leading-relaxed">
                        Comprehensive coverage on all structural components
                    </p>
                </div>

                <div class="text-center fade-in" data-fade>
                    <div
                        class="w-16 h-16 bg-gradient-to-br from-orange-500/20 to-orange-600/20 rounded-2xl flex items-center justify-center mx-auto mb-6 border border-orange-500/30">
                        <span class="text-4xl">💬</span>
                    </div>
                    <h3 class="text-xl font-bold mb-3">24/7 Support</h3>
                    <p class="text-gray-400 text-sm leading-relaxed">
                        Expert guidance available whenever you need assistance
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Premium CTA Section -->
    <section id="cta"
        class="py-32 px-6 bg-gradient-to-br from-orange-600 via-orange-500 to-orange-700 relative overflow-hidden">
        <div
            class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHZpZXdCb3g9IjAgMCA2MCA2MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZyBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiPjxwYXRoIGQ9Ik0zNiAxOGMzLjMxNCAwIDYgMi42ODYgNiA2cy0yLjY4NiA2LTYgNi02LTIuNjg2LTYtNiAyLjY4Ni02IDYtNiIgc3Ryb2tlPSJyZ2JhKDI1NSwyNTUsMjU1LDAuMSkiLz48L2c+PC9zdmc+')] opacity-30">
        </div>

        <div class="max-w-4xl mx-auto text-center relative z-10 fade-in" data-fade>
            <h2 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-6 text-white tracking-tight">
                Transform Your Space.<br />Elevate Your Performance.
            </h2>
            <p class="text-xl text-white/90 mb-10 max-w-2xl mx-auto font-light">
                Join thousands of athletes who've upgraded their training with GymWithin's premium equipment.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                <button
                    class="magnetic-btn bg-black text-white px-10 py-5 rounded-full font-semibold text-lg hover:bg-gray-900 w-full sm:w-auto">
                    Shop All Equipment
                </button>
                <button
                    class="bg-white/20 backdrop-blur-sm text-white px-10 py-5 rounded-full font-semibold text-lg hover:bg-white/30 transition-all border border-white/30 w-full sm:w-auto">
                    Schedule Consultation
                </button>
            </div>

            <!-- Trust Indicators -->
            <div class="mt-16 pt-16 border-t border-white/20">
                <div class="grid grid-cols-3 gap-8 max-w-3xl mx-auto">
                    <div>
                        <div class="text-4xl font-bold text-white mb-1">15K+</div>
                        <div class="text-white/80 text-sm">Happy Customers</div>
                    </div>
                    <div>
                        <div class="text-4xl font-bold text-white mb-1">4.9★</div>
                        <div class="text-white/80 text-sm">Average Rating</div>
                    </div>
                    <div>
                        <div class="text-4xl font-bold text-white mb-1">98%</div>
                        <div class="text-white/80 text-sm">Would Recommend</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Premium Footer -->
    <footer id="footer" class="bg-black border-t border-white/10 py-16 px-6">
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-12 mb-12">
                <div class="lg:col-span-2">
                    <div class="flex items-center space-x-2 mb-4">
                        <div class="w-8 h-8 bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg"></div>
                        <span class="text-xl font-bold">GymWithin</span>
                    </div>
                    <p class="text-gray-400 text-sm leading-relaxed mb-6 max-w-xs">
                        Transforming fitness through premium equipment and unwavering commitment to excellence.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#"
                            class="w-10 h-10 bg-white/5 hover:bg-white/10 rounded-full flex items-center justify-center transition-all border border-white/10">
                            <span class="text-sm">📷</span>
                        </a>
                        <a href="#"
                            class="w-10 h-10 bg-white/5 hover:bg-white/10 rounded-full flex items-center justify-center transition-all border border-white/10">
                            <span class="text-sm">f</span>
                        </a>
                        <a href="#"
                            class="w-10 h-10 bg-white/5 hover:bg-white/10 rounded-full flex items-center justify-center transition-all border border-white/10">
                            <span class="text-sm">▶</span>
                        </a>
                    </div>
                </div>

                <div>
                    <h4 class="font-semibold mb-4 text-sm uppercase tracking-wider text-gray-300">Products</h4>
                    <ul class="space-y-3 text-sm">
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Cardio
                                Equipment</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Strength
                                Training</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Accessories</a>
                        </li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Bundles &
                                Packages</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-semibold mb-4 text-sm uppercase tracking-wider text-gray-300">Support</h4>
                    <ul class="space-y-3 text-sm">
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Contact Us</a>
                        </li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Warranty
                                Information</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Shipping &
                                Delivery</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Returns &
                                Exchanges</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-semibold mb-4 text-sm uppercase tracking-wider text-gray-300">Company</h4>
                    <ul class="space-y-3 text-sm">
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">About
                                GymWithin</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Our Story</a>
                        </li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Careers</a>
                        </li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Press &
                                Media</a></li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-white/10 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-gray-500 text-sm">
                    &copy; 2025 GymWithin, Inc. All rights reserved.
                </p>
                <div class="flex gap-6 text-sm">
                    <a href="#" class="text-gray-500 hover:text-white transition-colors">Privacy Policy</a>
                    <a href="#" class="text-gray-500 hover:text-white transition-colors">Terms of Service</a>
                    <a href="#" class="text-gray-500 hover:text-white transition-colors">Cookie Settings</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scroll to Top Button -->
    <button id="scrollToTop" class="scroll-to-top" aria-label="Scroll to top">
        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
        </svg>
    </button>

    <!-- Chatbot Widget -->
    <div class="chatbot-widget">
        <div id="chatbotWindow" class="chatbot-window">
            <div class="chatbot-header">
                <div>
                    <h3 class="font-bold text-white">GymWithin Assistant</h3>
                    <p class="text-xs text-white/80">Always here to help</p>
                </div>
                <button id="closeChatbot" class="text-white hover:text-white/80 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div id="chatbotMessages" class="chatbot-messages">
                <div class="chatbot-message bot">
                    👋 Hello! I'm your GymWithin assistant. How can I help you today?
                </div>
                <div class="chatbot-message bot">
                    I can help you with:
                    <br />• Product recommendations
                    <br />• Pricing and financing
                    <br />• Shipping information
                    <br />• Technical support
                </div>
            </div>

            <div class="chatbot-input-area">
                <input type="text" id="chatbotInput" class="chatbot-input" placeholder="Type your message..."
                    autocomplete="off">
                <button id="chatbotSend" class="chatbot-send">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                    </svg>
                </button>
            </div>
        </div>

        <button id="chatbotButton" class="chatbot-button">
            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
            </svg>
        </button>
    </div>

    <!-- Motion.dev Scroll Animation Script -->
    <script>
        const {
            scroll,
            animate,
            inView
        } = Motion;

        // Hero Image Scroll Animation with Motion.dev
        const heroImage = document.getElementById('heroImage');
        const heroWrapper = document.querySelector('.hero-wrapper');

        scroll(
            animate(heroImage, {
                scale: [1, 1.3],
                opacity: [1, 0.4],
            }), {
            target: heroWrapper,
            offset: ['start start', 'end start']
        }
        );

        // Smooth fade-in animations for content sections
        const fadeElements = document.querySelectorAll('[data-fade]');

        fadeElements.forEach((el, index) => {
            inView(
                el,
                () => {
                    animate(
                        el, {
                        opacity: [0, 1],
                        y: [30, 0]
                    }, {
                        duration: 0.8,
                        delay: index * 0.1,
                        easing: [0.4, 0, 0.2, 1]
                    }
                    );
                }, {
                amount: 0.3
            }
            );
        });

        // Add magnetic effect to CTA buttons
        const magneticBtns = document.querySelectorAll('.magnetic-btn');

        magneticBtns.forEach(btn => {
            btn.addEventListener('mouseenter', (e) => {
                animate(
                    btn, {
                    scale: 1.05
                }, {
                    duration: 0.3,
                    easing: [0.4, 0, 0.2, 1]
                }
                );
            });

            btn.addEventListener('mouseleave', (e) => {
                animate(
                    btn, {
                    scale: 1
                }, {
                    duration: 0.3,
                    easing: [0.4, 0, 0.2, 1]
                }
                );
            });
        });

        // Scroll to Top Button
        const scrollToTopBtn = document.getElementById('scrollToTop');

        window.addEventListener('scroll', () => {
            if (window.scrollY > 500) {
                scrollToTopBtn.classList.add('visible');
            } else {
                scrollToTopBtn.classList.remove('visible');
            }
        });

        scrollToTopBtn.addEventListener('click', () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });

        // Chatbot Functionality
        const chatbotButton = document.getElementById('chatbotButton');
        const chatbotWindow = document.getElementById('chatbotWindow');
        const closeChatbot = document.getElementById('closeChatbot');
        const chatbotInput = document.getElementById('chatbotInput');
        const chatbotSend = document.getElementById('chatbotSend');
        const chatbotMessages = document.getElementById('chatbotMessages');

        // Toggle chatbot window
        chatbotButton.addEventListener('click', () => {
            chatbotWindow.classList.toggle('active');
            if (chatbotWindow.classList.contains('active')) {
                chatbotInput.focus();
            }
        });

        closeChatbot.addEventListener('click', () => {
            chatbotWindow.classList.remove('active');
        });

        // Send message function
        function sendMessage() {
            const message = chatbotInput.value.trim();
            if (!message) return;

            // Add user message
            const userMsg = document.createElement('div');
            userMsg.className = 'chatbot-message user';
            userMsg.textContent = message;
            chatbotMessages.appendChild(userMsg);

            // Clear input
            chatbotInput.value = '';

            // Scroll to bottom
            chatbotMessages.scrollTop = chatbotMessages.scrollHeight;

            // Simulate bot response
            setTimeout(() => {
                const botMsg = document.createElement('div');
                botMsg.className = 'chatbot-message bot';

                // Simple response logic
                const lowerMessage = message.toLowerCase();
                if (lowerMessage.includes('price') || lowerMessage.includes('cost')) {
                    botMsg.textContent =
                        'Our equipment ranges from $1,299 to $2,499. We also offer flexible financing options starting at $54/month. Would you like to know more about a specific product?';
                } else if (lowerMessage.includes('shipping') || lowerMessage.includes('delivery')) {
                    botMsg.textContent =
                        'We offer free white-glove delivery and installation on all equipment. Delivery typically takes 5-7 business days. Where are you located?';
                } else if (lowerMessage.includes('warranty')) {
                    botMsg.textContent =
                        'All GymWithin equipment comes with a lifetime warranty on frames and a 5-year warranty on parts. We stand behind our quality!';
                } else if (lowerMessage.includes('treadmill')) {
                    botMsg.textContent =
                        'Our Pro Treadmill X1 features advanced cushioning, smart tracking, and a powerful motor. It\'s perfect for serious runners. Would you like to schedule a demo?';
                } else {
                    botMsg.textContent =
                        'Thanks for reaching out! Our team can help you with that. You can also call us at 1-800-GYM-WITHIN or email support@gymwithin.com. Is there anything specific I can help with?';
                }

                chatbotMessages.appendChild(botMsg);
                chatbotMessages.scrollTop = chatbotMessages.scrollHeight;
            }, 800);
        }

        // Send on button click
        chatbotSend.addEventListener('click', sendMessage);

        // Send on Enter key
        chatbotInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                sendMessage();
            }
        });
    </script>
</body>

</html>