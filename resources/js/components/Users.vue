<template>
<div>
    <div class="container">
        <div class="row mt-5">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                    <h3>Users Component</h3>
                    <div class="card-tools">
                        <button class="btn btn-primary" @click="newModal">
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
                                        <button @click="updateModal(user)"><i class="fas fa-edit green"></i></button>
                                        /
                                        <button @click="deleteUser(user)"><i class="fas fa-trash red"></i></button>
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
    <div class="modal fade" id="newModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" v-show="editmode">New User</h5>
                    <h5 class="modal-title" v-show="!editmode">Update User</h5>
                         <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span class="red" aria-hidden="true">&times;</span>
                        </button>
                </div>
                <form @submit.prevent="editmode ? createUser() : updateUser()">
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
                        <button v-show="editmode" type="submit" class="btn btn-primary">Add New</button>
                        <button v-show="!editmode" type="submit" class="btn btn-primary">Update User</button>
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
                editmode:false,
                users:[],
                form: new Form({
                    id:'',
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
            newModal(){
                this.form.reset();
                $("#newModal").modal('show');
            },
            updateModal(user){
                $("#newModal").modal('show');
                this.form.fill(user);
            },
            loadUsers(){
                this.$Progress.start();
                axios.get('api/user')
                .then( ({data}) => {
                    (this.users = data.data)
                     this.$Progress.finish()
                },(respon) => this.$Progress.fail() );
                
            },
            createUser(){
                this.$Progress.start();
                this.form.post('api/user')
                .then((data)=>{
                    this.$Progress.finish();
                    $('#modelId').modal('hide');               
                     
                    toast.fire({
                    type: 'success',
                    title: 'User Created in successfully'
                    })
                    Fire.$emit('afterCreated');
                },(respon)=>{ this.$Progress.fail() });
            },
            updateUser(){
                this.$Progress.start();
                this.form.put('api/user/'+(this.form.id))
                .then( (response) => {  
                    Fire.$emit('afterCreated');
                    $('#newModal').modal('hide'),
                    this.$Progress.finish(),
                    toast.fire({
                        type: 'success',
                        title: 'User Updated in Successfully'
                    }) ;
                })
                .catch( (error) => this.$Progress.fail()  );
     
            },
            deleteUser(user){
                Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.value) {
                        this.form.delete('api/user/'+user.id)
                        .then((response)=>{
                            Fire.$emit('afterCreated');
                            Swal.fire(
                            'Deleted!',
                            'Your file has been deleted.',
                            'success'
                            )
                        })
                        .catch((error)=>{
                            console.log(error);
                        })
                    }else if(result.dismiss === Swal.DismissReason.cancel ){
                        Swal.fire('Cancelled','Your imaginary user is safe:)','error');
                    }
                 })
            }
        },
        mounted() {
            console.log('Component mounted.')
        },
        created(){
            this.loadUsers();
            Fire.$on('afterCreated',()=>{
                this.loadUsers();
            });
        }
 
    }
</script>
