<template>
<div>

    <form action="#" @submit.prevent="edit ? updateNote(note.id) : createNote()" class="d-lg-flex">
        <div class="form-group">
            <label>Type</label>
            <select v-model="note.type_id" name="type_id" class="form-control" >
                <option v-for="type in typeList" v-bind:value="type.id">{{type.name}}</option>
            </select>
        </div>


        <div class="form-group">
            <label>Category</label>
            <select v-model="note.category_id" name="category_id" class="form-control">
                <option v-for="category in currentType.categories">{{category.name}}</option>
            </select>
        </div>

        <div class="form-group">
            <label>Amount</label>
            <input v-model="note.amount" type="number" name="amount" class="form-control">
        </div>
        <div class="form-group">
            <label>Date</label>
            <input v-model="note.created_at" type="text" name="created_at"class="form-control">
        </div>
        <div class="form-group">
            <label>Comment</label>
            <input v-model="note.comment" type="text" name="comment" class="form-control">
        </div>
        <div class="form-group">
            <button v-show="!edit" type="submit" class="btn btn-primary">New Note</button>
            <button v-show="edit" type="submit" class="btn btn-primary">Update Note</button>
        </div>
    </form>


    <table class="table table-striped">
        <thead>
            <tr>
                <th>Type</th>
                <th>category</th>
                <th>Amount</th>
                <th>Date</th>
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
        <td>{{note.created_at}} </td>
        <td>{{note.comment}}</td>
        <td><button @click="showNote(note.id)" class="btn btn-success btn-xs">Edit</button>
        <button @click="deleteNote(note.id)" class="btn btn-danger btn-xs">Delete</button></td>
      </tr>
        </tbody>
    </table>





</div>
</template>

<script>
    export default {
        data: function() {
            return {
                typeList: [],
                type: {
                    id: '',
                    name: ''
                },

                edit: false,
                type: '',
                noteList: [],
                note: {
                    id: '',
                    type_id: '',
                    category_id: '',
                    amount: '',
                    comment: '',
                    created_at: '',
                    updated_at: ''
                }
            }
        },
        mounted() {
            console.log('Component mounted.');
            this.fetchNotesList();
            this.fetchTypeList();
        },
        methods: {
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
                    self.note.created_at = response.data.created_at;
                    self.note.comment = response.data.comment;
                })
                self.edit = true;
            },
            createNote: function(){
                console.log('Creating note...');
                let self = this;
                let params = Object.assign({}, self.note);
                axios.post('/api/notes/store', params).then(function() {
                    self.note.type_id = '';
                    self.note.category_id = '';
                    self.note.amount = '';
                    self.note.created_at = '';
                    self.note.comment = '';
                    self.edit = false;
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
                    self.note.created_at = '';
                    self.note.comment = '';
                    self.edit = false;
                    self.fetchNotesList();
                }).catch(function(error) {
                    console.log(error);
                });
            },
            deleteNote: function(id) {
                if(!confirm("Are you sure delete that?")) return;
                let self = this;
                axios.delete('/api/notes/'+ id).then(function(response) {
                    self.fetchNoteList();
                }).catch(function(error) {
                    console.log(error);
                });
            }
        },
    }
</script>
