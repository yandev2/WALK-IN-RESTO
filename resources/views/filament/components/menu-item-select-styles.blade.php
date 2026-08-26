<style>
    .menu-select-option {
        display: flex;
        align-items: center;
        gap: 0.625rem;
        min-width: 0;
    }

    .menu-select-option__thumb {
        width: 2.5rem;
        height: 2.5rem;
        flex-shrink: 0;
        border-radius: 0.5rem;
        object-fit: cover;
        background: rgb(241 245 249);
        border: 1px solid rgb(226 232 240);
    }

    .menu-select-option__placeholder {
        display: flex;
        align-items: center;
        justify-content: center;
        color: rgb(148 163 184);
        font-size: 0.625rem;
        font-weight: 600;
        text-transform: uppercase;
    }

    .menu-select-option__body {
        min-width: 0;
        flex: 1;
    }

    .menu-select-option__name {
        font-size: 0.875rem;
        font-weight: 500;
        line-height: 1.25;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .menu-select-option__meta {
        display: flex;
        align-items: center;
        gap: 0.375rem;
        margin-top: 0.125rem;
        font-size: 0.75rem;
        color: rgb(100 116 139);
    }

    .menu-select-option__discount {
        border-radius: 0.25rem;
        background: rgb(254 243 199);
        color: rgb(180 83 9);
        padding: 0 0.25rem;
        font-size: 0.625rem;
        font-weight: 600;
    }
</style>
