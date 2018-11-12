<template>
<div class="d-lg-flex m-2">
    <div class="mt-2 w-75">
        <div class="mt-4 d-sm-flex">
            <label class="font-weight-bold mt-2 mr-2 ">Фильтровать по категории:</label>
            <select v-model="neededCategory" class="form-control mb-2 w-25">
                <option v-bind:value="'all'">Все</option>
                <option v-bind:value="category.id" v-for="category in categoryList">{{category.name}}</option>
            </select>
            <a href="#" v-show="form === false" v-on:click.prevent="formMy()" class="btn btn-primary mb-2 ml-4">Новая запись</a>
        </div>
        <table class="table table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>Тип записи</th>
                    <th>Категория</th>
                    <th>Сумма</th>
                    <th>Дата</th>
                    <th>Комментарий</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
          <tr v-for="note in sortByCat">
            <td><strong>{{note.type.name}}</strong></td>
            <td>{{note.category.name}}</td>
            <td>{{note.amount}}</td>
            <td>{{note.note_date}} </td>
            <td>{{note.comment}}</td>
            <td><button @click="showNote(note.id)" class="btn btn-success btn-xs">Редактировать</button>
            <button @click="deleteNote(note.id)" class="btn btn-danger btn-xs">Удалить</button></td>
          </tr>
            </tbody>
        </table>
    </div>

    <div class="mt-2">
<!--         <a v-show="form === false" v-on:click="formMy()" class="btn btn-info mt-4 mb-5 ml-5">Новая запись</a> -->
        <div v-show="form === true" class="mb-5 mt-4 ml-5">
            <form action="#" @submit.prevent="edit ? updateNote(note.id) : createNote()">
                <div class="form-group">
                    <label class="badge">Тип Записи</label>
                    <select v-model="note.type_id" name="type_id" class="form-control" required="required" >
                        <option v-for="type in typeList" v-bind:value="type.id">{{type.name}}</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="badge">Категория</label>
                    <select v-model="note.category_id" name="category_id" class="form-control" required="required">
                        <option v-if="category.type_id == note.type_id" v-for="category in categoryList" v-bind:value="category.id" >{{category.name}}</option>
                    </select>
                </div>

                <div  class="form-group">
                    <label class="badge">Сумма</label>
                    <input v-model="note.amount" type="number" required="required" name="amount" class="form-control">
                </div>
                <div  class="form-group">
                    <label class="badge">Дата</label>
                    <input v-model="note.note_date" required="required" type="date" id="date" name="note_date"class="form-control">
                </div>
                <div   class="form-group">
                    <label class="badge">Комментарий</label>
                    <textarea v-model="note.comment" type="text" name="comment" class="form-control"></textarea>
                </div>
                <div class="form-group ml-5">
                    <div  class="form-group">
                        <button v-show="!edit" type="submit" class="btn btn-primary">Сделать запись</button>
                        <button v-show="edit" type="submit" class="btn btn-primary">Обновить</button>
                        <a v-on:click="clearForm(), formMy()" v-show="note.category_id !== '', edit" class="btn btn-primary mb-5 mt-5 ml-5">Назад</a>
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
                neededCategory: 'all',
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
                    let mm = this.getMonth() + 1 +'-';
                    let dd = this.getDate();
                    return [this.getFullYear()+ '-',
                        (mm>9 ? '' : '') + mm,
                        (dd>9 ? '' : '0') + dd
                    ].join('');
                };
                var date = new Date();
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
                    this.noteList = response.data.reverse();
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
                    self.note.comment = '';
                    self.edit = false;
                    self.form = false;
                    self.fetchNotesList();
                }).catch(function(error){
                    console.log(error);
                    sortByCat();
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
                sortByCat();
            }
        },
  
        computed: {
            sortByCat() {
                if (this.neededCategory == 'all')
                    return this.noteList
                else
                    return this.noteList.filter(note => note.category_id == this.neededCategory)
            }
        }

    }
</script>
