@push('styles')
<style>
.admin-author-ratings {
    display: grid;
    gap: 10px;
    padding: 16px 18px 20px;
}
.admin-author-rating-row {
    display: grid;
    grid-template-columns: 48px minmax(0, 1fr) 40px;
    align-items: center;
    gap: 12px;
}
.admin-author-rating-row > div {
    height: 8px;
    overflow: hidden;
    border-radius: 999px;
    background: #f0f1f3;
}
.admin-author-rating-row > div > i {
    display: block;
    height: 100%;
    background: #b30000;
}
.admin-author-comment {
    display: -webkit-box;
    overflow: hidden;
    color: #6b7078;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
}
.admin-author-notifications {
    display: grid;
}
.admin-author-notif {
    display: grid;
    grid-template-columns: auto minmax(0, 1fr) 36px;
    gap: 14px;
    align-items: start;
    padding: 16px 18px;
    border-bottom: 1px solid #f0f1f3;
}
.admin-author-notif:last-child {
    border-bottom: 0;
}
.admin-author-notif.is-unread {
    background: #fffaf9;
}
.admin-author-notif-meta {
    display: flex;
    flex-wrap: wrap;
    align-items: baseline;
    justify-content: space-between;
    gap: 8px;
}
.admin-author-notif p {
    margin: 4px 0 8px;
    color: #6b7078;
    font-size: .9rem;
}
.admin-author-notif a {
    color: #b30000;
    font-size: .8rem;
    font-weight: 700;
}
.admin-author-notif-delete {
    display: grid;
    width: 34px;
    height: 34px;
    place-items: center;
    border: 1px solid #e6e7ea;
    border-radius: 9px;
    background: #fff;
    color: #8a8e95;
}
.admin-author-notif-delete:hover {
    border-color: #f0c8c8;
    background: #fff0f0;
    color: #b30000;
}
.admin-books-stats .admin-books-stat {
    text-decoration: none;
    color: inherit;
}
</style>
@endpush
