<template>
<div>
    <div class="container">
        <div class="row mt-5">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                    <h3>Users Component</h3>
                    <div class="card-tools">
                        <button class="btn btn-primary" data-toggle="modal" data-target="#modelId">
                            Add New 
                            <i class="fas fa-user-plus fa-fw"></i></button>
                    </div>
                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover">
                            <tbody>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Type</th>
                                    <th>Created At</th>
                                    <th>Modify</th>
                                </tr>
                                <tr v-for="(user,index) in users" :key="user.id">
                                    <td>{{index}}</td>
                                    <td>{{ user.name }}</td>
                                    <td>{{ user.email }}</td>
                                    <td>{{ user.type }}</td>
                                    <td>{{ user.created_at }}</td>
                            
                                    <td>
                                        <a href="#"><i class="fas fa-edit green"></i></a>
                                        /
                                        <a href=""><i class="fas fa-trash red"></i></a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
 
    
    <!-- Modal -->
    <div class="modal fade" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modal title</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span class="red" aria-hidden="true">&times;</span>
                        </button>
                </div>
                <form @submit.prevent="create">
                    <div class="modal-body">
                        <div class="form-group">
                            <input v-model="form.name" type="text" name="name"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                            <has-error :form="form" field="name"></has-error>
                        </div>
                        <div class="form-group">
                            <input v-model="form.email" type="email" name="email"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('email') }">
                            <has-error :form="form" field="email"></has-error>
                        </div>
                        <div class="form-group">
                            <textarea v-model="form.bio" type="text" name="type"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('type') }">
                            </textarea>
                            <has-error :form="form" field="type"></has-error>
                        </div>
                        <div class="form-group">
                            <select v-model="form.type" :class="{'is-invalid':form.errors.has('type')}" name="type" id="type" class="form-control">
                                <option value="">Select User Type</option>
                                <option value="admin">Admin</option>
                                <option value="user">Standard User</option>
                                <option value="author">Author</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <input type="password" v-model="form.password" class="form-control" name="password"
                            :class="{'is-invalid':form.errors.has('password')}">
                            <has-error :form="form" field="password"></has-error>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

</template>

<script>
import Form from 'vform'

    export default {
        data(){
            return {
                users:[],
                form: new Form({
                    name:'',
                    email:'',
                    password:'',
                    type:'',
                    bio:'',
                    photo:'',
                })
            }
        },
        methods:{
            loadUsers(){
                axios.get('api/user')
                .then(({data})=> { this.users = data; console.log(data)} );
            },
            create(){
                this.form.post('api/user')
                .then(({data})=> { console.log(data) })
            }
        },
        mounted() {
            console.log('Component mounted.')
        },
        created(){
            this.loadUsers();
        },
    }
</script>
