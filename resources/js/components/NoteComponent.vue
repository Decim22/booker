<template>
<div class="d-lg-flex">
    <div>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th class="mask flex-center rgba-red-strong">Type</th>
                    <th class="mask flex-center rgba-red-strong">category</th>
                    <th class="mask flex-center rgba-red-strong">Amount</th>
                    <th class="mask flex-center rgba-red-strong">Date</th>
                    <th>Comment</th>
                    <th>Control</th>
                </tr>
            </thead>
            <tbody>
          <tr v-for="note in noteList">
            <td><strong>{{note.type.name}}</strong></td>
            <!-- <td>{{note.category_id}} </td> -->
            <td>{{note.category.name}}</td>
            <td>{{note.amount}}</td>
            <td>{{note.note_date}} </td>
            <td>{{note.comment}}</td>
            <td><button @click="showNote(note.id)" class="btn btn-success btn-xs">Edit</button>
            <button @click="deleteNote(note.id)" class="btn btn-danger btn-xs">Delete</button></td>
          </tr>
            </tbody>
        </table>
    </div>



    <div>
        <a v-show="form === false" v-on:click="formMy()" class="btn btn-primary mb-5 mt-5 ml-5">New Note</a>
        <div v-show="form === true" class="mb-5 mt-2 ml-5">
            <form action="#" @submit.prevent="edit ? updateNote(note.id) : createNote()">
                <div class="form-group">
                    <label>Choose Type</label>
                    <select v-model="note.type_id" name="type_id" class="form-control" >
                        <option v-for="type in typeList" v-bind:value="type.id">{{type.name}}</option>
                    </select>
                </div>

                <div  class="form-group">
                    <label>Choose Category</label>
                    <select v-model="note.category_id" name="category_id" class="form-control">
                        <option v-if="category.type_id == note.type_id" v-for="category in categoryList" v-bind:value="category.id" >{{category.name}}</option>
                    </select>
                </div>

                <div  class="form-group">
                    <label>Insert Amount</label>
                    <input v-model="note.amount" type="number" required="required" name="amount" class="form-control">
                </div>
                <div  class="form-group">
                    <label>Insert Date</label>
                    <input v-model="note.note_date" required="required" type="date" id="date" name="note_date"class="form-control">
                </div>
                <div   class="form-group">
                    <label>Write a Comment</label>
                    <input v-model="note.comment" type="text" name="comment" class="form-control">
                </div>
                <div class="form-group ml-5">
                    <div  class="form-group">
                        <button v-show="!edit" type="submit" class="btn btn-primary">Submit</button>
                        <button v-show="edit" type="submit" class="btn btn-primary">Update Note</button>
                        <a v-on:click="clearForm()" v-show="note.category_id !== '', edit" class="btn btn-primary mb-5 mt-5 ml-5">New Note</a>
                    </div>
                </div>

            </form>
        </div>
    </div>


</div>
</template>

<script>
    export default {
        data: function() {
            return {
                form: false,
                typeList: [],
                type: {
                    id: '',
                    name: '',
                    categories: []
                },
                categoryList: [],
                category: {
                    id: '',
                    type_id: '',
                    name: '',
                    notes: []
                },
                edit: false,
                noteList: [],
                note: {
                    id: '',
                    type_id: '',
                    category_id: '',
                    amount: '',
                    comment: '',
                    note_date: '',
                    created_at: '',
                    updated_at: ''
                }
            }
        },
        mounted() {
            console.log('Component mounted.');
            this.fetchNotesList();
            this.fetchTypeList();
            this.fetchCategoryList();
        },
        methods: {
            formMy:function() {
                self = this;
                this.form = true;
                    Date.prototype.yyyymmdd = function() {
                        let mm = this.getMonth() + 1 +'-'; // getMonth() is zero-based
                        let dd = this.getDate();
                        return [this.getFullYear()+ '-',
                            (mm>9 ? '' : '') + mm,
                            (dd>9 ? '' : '0') + dd
                        ].join('');
                };
                var date = new Date();
                // console.log(date.yyyymmdd());
                this.note.note_date = date.yyyymmdd();
                console.log(this.note.note_date);
            },
            fetchCategoryList:function() {
                console.log('fetching categories');
                axios.get('/api/categories')
                .then((response)=> {
                    console.log(response.data);
                    this.categoryList = response.data;
                }).catch((error)=> {
                    console.log(error);
                });            
            },
            fetchTypeList:function() {
                console.log('Fetching Types.....');
                axios.get('/api/types')
                .then((response)=> {
                    console.log(response.data);
                    this.typeList = response.data;
                }).catch((error)=> {
                    console.log(error);
                });
            },
            fetchNotesList:function(){
                console.log('Fetching notes.....');
                axios.get('/api/notes')
                  .then((response) => {
                    console.log(response.data);
                    this.noteList = response.data;
                  }).catch((error) => {
                    console.log(error);
                  });
            },
            showNote:function(id) {
                let self = this;
                axios.get('/api/notes/'+id).then(function(response) {
                    self.note.id = response.data.id;
                    self.note.type_id = response.data.type_id;
                    self.note.category_id = response.data.category_id;
                    self.note.amount = response.data.amount;
                    self.note.note_date = response.data.note_date;
                    self.note.comment = response.data.comment;
                })
                self.form = true;
                self.edit = true;
            },
            clearForm:function() {
                let self = this;
                    self.note.id = '';
                    self.note.type_id = '';
                    self.note.category_id = '';
                    self.note.amount = '';
                    self.note.note_date = '';
                    self.note.comment = '';
                    self.form = true;
                    self.edit = false;
            },
            createNote: function(){
                console.log('Creating note...');
                let self = this;
                let params = Object.assign({}, self.note);
                axios.post('/api/notes/store', params).then(function() {
                    self.note.type_id = '';
                    self.note.category_id = '';
                    self.note.amount = '';
                    self.note.note_date = '';
                    // self.note.created_at = '';
                    self.note.comment = '';
                    self.edit = false;
                    self.form = false;
                    self.fetchNotesList();
                }).catch(function(error){
                    console.log(error);
                });
                return;
            },
            updateNote: function(id){
                console.log('Updating note '+id+'...');
                let self = this;
                let params = Object.assign({}, self.note);
                axios.patch('/api/notes/'+ id, params).then(function() {
                    self.note.type_id = '';
                    self.note.category_id = '';
                    self.note.amount = '';
                    self.note.note_date = '';
                    // self.note.created_at = '';
                    self.note.comment = '';
                    self.edit = false;
                    self.form = false;
                    self.fetchNotesList();
                }).catch(function(error) {
                    console.log(error);
                });
            },
            deleteNote: function(id) {
                if(!confirm("Are you sure delete that?")) return;
                let self = this;
                axios.delete('/api/notes/'+ id).then(function(response) {
                    self.fetchNotesList();
                    self.form = false;
                }).catch(function(error) {
                    console.log(error);
                });
            }
        },
    }
</script>
