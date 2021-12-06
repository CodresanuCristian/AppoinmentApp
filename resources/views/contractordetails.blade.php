<div class="container mt-5 border border-black">
    <h1 class="text-center">Details</h1>
    <!-- ADD NEW CONTRACTOR FIELD -->
    <form id="addnewcontractorform" method="POST" action="/addnewcontractor">
        @csrf
        <input type="text" name="newcontractor" placeholder="Name">
        @error('newcontractor') <div style="color:red">{{ $message }}</div> @enderror
        <input type="password" name="newcontractorpass" placeholder="Password">
        @error('newcontractorpass') <div style="color:red">{{ $message }}</div> @enderror
        <button type="submit" class="btn btn-success" id="newcontractorbtn">Add new conctractor</button>
    </form>
    
    <!-- DELETE CONTRACTOR FIELD -->
    <form id="deletecontractorfield" method="GET" action="/deletecontractor">
        @csrf
        <select name="deletecontractor" id="deletecontractor"></select>
        @error('deletecontractor') <div style="color:red">{{ $message }}</div> @enderror
        <button type="submit" class="btn btn-success" id="deletecontractorbtn">Delete contractor</button>
    </form>

    <!-- ADD DAYS OFF -->
    <form id="adddaysoffform" method="POST" action="/adddaysoff">
        @csrf
        <input type="text" name="adddaysoff" id="adddaysoff" placeholder="Choose from calendar" readonly> 
        @error('adddaysoff') <div style="color:red">{{ $message }}</div> @enderror
        <button type="submit" class="btn btn-success" id="adddaysoff">Add day off</button>
    </form>

    <!-- DELETE DAYS OFF -->
    <form id="deletedaysoffform" method="GET" action="/deletedaysoff">
        @csrf
        <select name="deletedaysoff" id="deletedaysoff"></select>
        @error('deletedaysoff') <div style="color:red">{{ $message }}</div> @enderror
        <button type="submit" class="btn btn-success" id="deletedaysoffbtn">Delete day off</button>
    </form>

    <!-- ADD HOLIDAYS -->
    <form id="addholidayform" method="post" action="/addholiday">
        @csrf
        <input type="date" name="startholiday" id="startholiday" placeholder="Start holiday">
        @error('startholiday') <div style="color:red">{{ $message }}</div> @enderror
        <input type="date" name="finishholiday" id="finishholiday" placeholder="Finish holiday">
        @error('finishholiday') <div style="color:red">{{ $message }}</div> @enderror
        <button type="submit" class="btn btn-success" id="addholidaybtn">Add holiday</button>
    </form>

    <!-- DELETE HOLIDAYS --> 
    <form id="deleteholidayform" method="GET" action="/deleteholiday">
        @csrf
        <select name="deleteholiday" id="deleteholiday"></select>
        @error('deleteholiday') <div style="color:red">{{ $message }}</div> @enderror
        <button type="submit" class="btn btn-success" id="deleteholidaysbtn">Delete holiday</button>
    </form>
</div>