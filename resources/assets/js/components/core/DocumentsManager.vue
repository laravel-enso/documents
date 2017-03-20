<template>

    <div :class="['box box-' + headerClass, { 'collapsed-box': collapsed }]">
        <div class="box-header with-border">
            <i :class="{ 'fa fa-files-o': !pictures, 'fa fa-picture-o': pictures }">
            </i>
            <h3 class="box-title">
                <slot name="documents-manager-title">
                </slot>
            </h3>
            <div class="box-tools pull-right">
                <i v-if="documentsList.length > 1"
                    class="fa fa-search">
                </i>
                <input type="text"
                    size="15"
                    class="documents-filter"
                    v-model="queryString"
                    v-if="documentsList.length > 1">
                <i class="fa fa-upload btn"
                    @click="openFileBrowser">
                </i>
                <span class="badge bg-orange">
                    {{ documentsList.length }}
                </span>
                <button type="button"
                    class="btn btn-box-tool btn-sm"
                    @click="getData">
                    <i class="fa fa-refresh">
                    </i>
                </button>
                <button class="btn btn-box-tool btn-sm"
                    data-widget="collapse">
                    <i class="fa fa-plus">
                    </i>
                </button>
            </div>
        </div>
        <div class="box-body"
            style="overflow-y: scroll; max-height: 300px">
            <input :id="'input-' + _uid"
                type="file"
                name="files[]"
                class="hidden"
                multiple="multiple"
                @change="uploadDocuments">
            <div class="col-md-12">
                <div class="list-group list-group-unbordered">
                    <button type="button"
                        class="list-group-item"
                        style="cursor: default"
                        v-for="(document, index) in filteredDocumentsList"
                        :data-featherlight-gallery="pictures"
                        :data-featherlight-filter="pictures ? 'a.gallery' : null">
                        <span>
                            {{ index + 1 }}.
                        </span>
                        <span v-if="!pictures">
                            <a href="#"
                                @click="openDocument(document.id)">
                                {{ document.original_name }}
                            </a>
                        </span>
                         <span v-if="pictures">
                            <a  class="gallery"
                                :href="'/core/documents/show/' + document.id">
                                {{ document.original_name }}
                             </a>
                            </span>
                        <span class="pull-right">
                            <a class="btn btn-xs btn-info"
                                :href="'/core/documents/download/' + document.id">
                                <i class="fa fa-cloud-download">
                                </i>
                            </a>
                            <a class="btn btn-xs btn-danger"
                                @click="confirmDelete(document.id)">
                                <i class="fa fa-trash-o">
                                </i>
                            </a>
                        </span>
                        <span class="pull-right margin-right-md text-primary">
                            {{ document.size | numberFormat}} Kb
                        </span>
                    </button>
                </div>
            </div>
        </div>
        <div class="overlay" v-if="loading">
            <i class="fa fa-spinner fa-spin spinner-custom" ></i>
        </div>
        <modal :show="showModal"
            @cancel-action="showModal = false"
            @commit-action="deleteDocument">
            <span slot="modal-body">
                <slot name="modal-body">
                </slot>
            </span>
            <span slot="modal-cancel">
                <slot name="modal-cancel">
                </slot>
            </span>
            <span slot="modal-ok">
                <slot name="modal-ok">
                </slot>
            </span>
        </modal>
    </div>

</template>

<script>

     export default {

        props: {

            id: {
                type: Number,
                required: true
            },
            type: {
                type: String,
                required: true
            },
            headerClass: {
                type: String,
                default: 'primary'
            },
            pictures: {
                type: Boolean,
                default: false
            },
            fileSizeLimit: {
                type: Number,
                default: 8388608
            },
            collapsed: {
                type: Boolean,
                default: true
            }
        },
        computed: {

            filteredDocumentsList: function() {

                if (this.queryString) {

                    return this.documentsList.filter((doc) => {

                        return doc.original_name.toLowerCase().indexOf(this.queryString.toLowerCase()) > -1;
                    })
                }

                return this.documentsList;
            },
        },
        data: function() {

            return {
                documentsList: [],
                itemToBeDeleted: null,
                uploadInput: null,
                showModal: false,
                queryString: "",
                validImageTypes: ["image/gif", "image/jpeg", "image/png"],
                maxFileSize: this.fileSizeLimit <= 8388608 ? this.fileSizeLimit : 8388608,
                loading: false
            }
        },
        watch: {

            'id': {
                handler: 'getData'
            }
        },
        methods: {

            getData: function() {

                this.loading = true;

                axios.get('/core/documents/list', { params: { id: this.id, type: this.type }}).then((response) => {

                    this.documentsList = response.data;
                    this.loading = false;
                });
            },
            openFileBrowser: function() {

                this.uploadInput.trigger('click');
            },
            uploadDocuments: function() {

                let formData = this.getFormData();

                if (formData.entries().next().done) {

                    return false;
                }

                formData.append("id", this.id);
                formData.append("type", this.type);

                this.loading = true;

                axios.post('/core/documents/upload', formData).then((response) => {

                    toastr[response.data.level](response.data.message);

                    response.data.errors.forEach(function(error) {

                        toastr['error'](error);
                    });

                    this.uploadInput.val('');
                    this.getData();
                });
            },
            getFormData: function() {

                let formData = new FormData(),
                    files = this.uploadInput[0].files;

                for (var i = 0; i < files.length; i++) {

                    if (!this.checkFileSize(files[i]) || !this.checkFileFormat(files[i])) {

                        continue;
                    }

                    formData.append("file_" + i, files[i]);
                }

                return formData;
            },
            checkFileSize: function(file) {

                if (file.size > this.maxFileSize) {

                    toastr["warning"]('File Size Limit of ' + this.maxFileSize + ' Kb excedeed by ' + file.name);

                    return false;
                }

                return true;
            },
            checkFileFormat: function(file) {

                if (this.pictures && this.validImageTypes.indexOf(file.type) === -1) {

                    toastr["warning"]('File ' + file.name + ' is not of picture format');

                    return false;
                }

                return true;
            },
            openDocument: function(id) {

                window.open('/core/documents/show/' + id, '_blank').focus();
            },
            confirmDelete: function(id) {

                this.itemToBeDeleted = id;
                this.showModal = true;
            },
            deleteDocument: function() {

                this.showModal = false;
                this.loading = true;

                axios.delete('/core/documents/destroy/' + this.itemToBeDeleted).then((response) => {

                    this.itemToBeDeleted = null;
                    toastr[response.data.level](response.data.message);
                    this.getData();
                });
            },
        },
        mounted: function() {

            this.uploadInput = $('input[id=input-' + this._uid + ']');
            this.getData();
        }
    }

</script>