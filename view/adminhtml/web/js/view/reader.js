define([
    'uiComponent',
    'ko',
    'jquery'
], function (Component, ko, $) {

    'use strict';

    return Component.extend({

        fileName: null,
        linesToShow: null,
        total: null,
        url: null,
        searchUrl: null,
        content: ko.observableArray([]),
        currentPage: ko.observable(1),
        maxPage: null,
        navDescFirstLine: ko.observable(''),
        navDescLastLine: ko.observable(''),
        searchValue: ko.observable(null),
        showSearchResult: ko.observable(false),

        defaults: {
            'template': 'Jentry_LogsManagement/logs/reader'
        },

        initialize: function (config) {
            this._super();

            this.fileName = config.fileName;
            this.linesToShow = parseInt(config.linesToShow ? config.linesToShow : 100);
            this.total = parseInt(config.totalLines);
            this.url = config.url;
            this.searchUrl = config.searchUrl;
            this.maxPage = Math.ceil(this.total / this.linesToShow);

            this.getContentToShow();

            return this;
        },

        getContentToShow: function () {
            $.ajax({
                type: 'post',
                url: this.url,
                data: {
                    form_key: window.FORM_KEY,
                    file: this.fileName,
                    linesToShow: this.linesToShow,
                    page: this.currentPage()
                },
                beforeSend: () => {
                    $('body').trigger('processStart');
                },
                success: (response) => {
                    this.content(response.content);
                    this.navDescFirstLine((this.currentPage() - 1) * this.linesToShow);
                    this.navDescLastLine(((this.currentPage() - 1) * this.linesToShow) + this.linesToShow);

                    $('body').trigger('processStop');
                }
            });
        },

        prevPage: function () {
            if (this.currentPage() > 1) {
                this.currentPage(this.currentPage() - 1);
                this.getContentToShow();
            }
        },

        nextPage: function () {
            if (this.currentPage() < this.maxPage) {
                this.currentPage(this.currentPage() + 1);
                this.getContentToShow();
            }
        },

        setPageDirectly: function (d, e) {
            this.currentPage(e.target.value);
            this.getContentToShow();
        },

        firstPage: function (d, e) {
            this.currentPage(1);
            this.getContentToShow();
        },

        lastPage: function (d, e) {
            this.currentPage(this.maxPage);
            this.getContentToShow();
        },

        searchHandler: function (d, e) {
            $.ajax({
                type: 'post',
                url: this.searchUrl,
                data: {
                    form_key: window.FORM_KEY,
                    file: this.fileName,
                    search: this.searchValue()
                },
                beforeSend: () => {
                    $('body').trigger('processStart');
                    this.showSearchResult(true);
                },
                success: (response) => {
                    this.content(response.content);
                    $('body').trigger('processStop');
                }
            });
        },

        resetSearchHandler: function (d, e) {
            this.searchValue(null);
            this.showSearchResult(false);
            this.getContentToShow();
        }
    });

});
