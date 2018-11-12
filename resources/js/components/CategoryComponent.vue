<template>
    <div class="container">
        <div class="d-lg-flex ml-5 mr-5 mt-4">
            <table class="table table-striped w-50">
                <thead class="thead-dark">
                    <tr>
                        <th>Тип</th>
                        <th>Название</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
              <tr v-for="category in categoryList">
                <td><strong>{{category.type.name}}</strong></td>
                <td>{{category.name}}</td>
                <td>
                    <button class="btn btn-success btn-xs" @click="showCategory(category.id)">Редактировать</button>
                    <button @click="deleteCategory(category.id)" class="btn btn-danger btn-xs">Удалить</button></td>
                </td>

              </tr>
                </tbody>
            </table>

            <div class="mt-2 ml-4">
            <a href="/notes" class="btn btn-primary mb-2">Записи</a>
                <a href="#" v-show="form === false" v-on:click.prevent="form = true" class="btn btn-primary mb-2">Добавить категорию</a>
                <form v-show="form === true" class="w-50" @submit.prevent="edit ? updateCategory(category.id) : createCategory()">
                   <div v-for="type in typeList" class="form-group">
                        <label class="badge">{{type.name}}</label>
                        <input required="required" name="type_id" v-model="category.type_id" type="radio" v-bind:value="type.id">
                        <br>
                    </div>
                    <div class="form-group">
                        <label class="badge">Название</label>
                        <input required="required" name="name" v-model="category.name" type="text">
                    </div>
                    <div class="form-group">
                        <button v-show="edit" type="submit"  class="btn btn-primary">Обновить</button>
                        <button v-show="!edit" type="submit" class="btn btn-primary">Создать</button>
                        <a v-on:click="clearForm()" v-show="category.name !== '', edit" class="btn btn-primary mt-2">Отмена</a>
                        </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data: function() {
            return{
                edit: false,
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
            }
        },
        mounted() {
            console.log('Component mounted.')
            this.fetchTypeList();
            this.fetchCategoryList();
        },
        methods: {
            clearForm:function() {
                let self = this;
                    self.category.id = '';
                    self.category.type_id = '';
                    self.category.name = '';
                    self.form = true;
                    self.edit = false;
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
            fetchCategoryList:function(){
            console.log('Fetching categories...');
            axios.get('/api/categories')
              .then((response) => {
                console.log(response.data);
                this.categoryList = response.data;
              }).catch((error) => {
                console.log(error);
              });
            },
            createCategory: function(){
                console.log('Creating category...');
                let self = this;
                let params = Object.assign({}, self.category);
                axios.post('/api/categories/store', params).then(function() {
                    self.category.type_id = ''
                    self.category.name = '';
                    self.edit = false;
                    self.fetchCategoryList();
                }).catch(function(error){
                    console.log(error);
                });
                return;
            },
            showCategory:function(id) {
                let self = this;
                axios.get('/api/categories/'+id).then(function(response) {
                    self.category.id = response.data.id
                    self.category.type_id = response.data.type_id;
                    self.category.name = response.data.name;
                })
                self.form = true;
                self.edit = true;
            },
            updateCategory: function(id){
                console.log('Updating category '+id+'...');
                let self = this;
                let params = Object.assign({}, self.category);
                axios.patch('/api/categories/'+ id, params).then(function() {
                    self.category.type_id = '';
                    self.category.name = '';
                    self.edit = false;
                    self.fetchCategoryList();
                }).catch(function(error) {
                    console.log(error);
                });
            },
            deleteCategory: function(id) {
                if(!confirm("Are you sure delete that?")) return;
                let self = this;
                axios.delete('/api/categories/'+ id).then(function(response) {
                    self.fetchCategoryList();
                }).catch(function(error) {
                    console.log(error);
                });
            }

    }
    }
</script>
