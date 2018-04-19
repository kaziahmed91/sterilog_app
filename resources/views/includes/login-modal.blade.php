<div class="modal fade " id="loginModal"  role="dialog" aria-labelledby="loginModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">User Login</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        
            <div class="modal-body">

                <form action="{{route('user.login')}}" method="POST" >
                          {{ csrf_field() }}

                    <div class="form-group">
                        <label for="user_name">Select User</label><br>
                        <select class="loginDropdown">
                            <option value="">Select a User</option>
                            @foreach($softUsers as $user)
                            <option data-id="{{$user->user_name}}">{{$user->first_name.' '.$user->last_name}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="user_name">User Name</label>
                        <input type="text" name="user_name" id="user_name" class="form-control" placeholder="" aria-describedby="helpId">
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" pattern="[0-9]*" inputmode="numeric" name="password" id="" class="form-control" placeholder="" aria-describedby="helpId">
                    </div>

                    <a href="" class="link">Forgot your password?</a>
                     <br><br>
   
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" >Login</button>
                    </div>

                </form>
                    
        </div>
    </div>
    
</div>