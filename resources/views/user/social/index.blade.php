 
            <div class="carManagement__wrapper">
                    <div class="breadcrumbWrapper">
                        <nav aria-label="breadcrumb">
                            <h3 class="fs-5 m-0 fw-500">Social Connect</h3>
                            <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{URL::to('/')}}/user/dashboard#index" onclick="dashboard()" >Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page"></li>
                            </ol>
                        </nav>
                    </div>
                
                     <div class="table-area">
                                <table class="table" id="dataTable">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Title</th>
                                            <!-- <th scope="col">login Url</th> -->
                                            <th scope="col">Action</th>
                                        </tr>
                                     </thead>

                                    <tbody>
                                        @php $i=1; @endphp
                                    @foreach($soicalInfo as $val)
                                    @php $id=$val->id; @endphp
                                    <tr>
                                    <td>{{$i}}</td>
                                    <td>{{$val->title}}</td>
                                    <!-- <td>{{$val->login_url}}</td> -->
                                    <td>

                                    <button class="btn btn-primary" id="connect"  onclick="connect({{ $id}})" >Connect</button></td>
                                    </td>
                                    </tr>
                                    @php $i++; @endphp
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>

<script>
    function connect(id) {
        if(id==1)
    {
        $.ajax({
            type:"GET",
            url:baseUrl+'/existfacebookuser',
      
        //dataType:'json',
   
            success:function(res)
            {
               
                if(res=='no')
                {
                        if(confirm("Are you sure want to connect facebook ?")) {
                        var url=baseUrl+'/facebook';
                        window.location=url;
                    } 
                    
                }
                else{
                    alert("Already facebook Login ");
                        return false;
                }

            }
        });
    }
    if(id==2)
    {
        if(confirm("Are you sure want to connect Instagram ?")) {
                        var url=baseUrl+'/instagrame';
                        window.location=url;
                    } 
    }
    if(id==3)
    {
        if(confirm("Are you sure want to connect LinkedIn  ?")) {
                        var url=baseUrl+'/user/dashboard#social_connect';
                        window.location=url;
                    } 
    }
    if(id==4)
    {
        if(confirm("Are you sure want to connect Youtube  ?")) {
                        var url=baseUrl+'/user/dashboard#social_connect';
                        window.location=url;
                    } 
    }
     
       
    }
      
    // if(confirm("Are you sure want to connect facebook ?")) {



    //     var url=baseUrl+'/facebook';
    //     window.location=url;
    // }
    // else {
    //     return false;

    // }


    
    </script>
      