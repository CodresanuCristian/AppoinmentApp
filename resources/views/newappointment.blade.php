<div class="container mt-5 border border-black">
    <h1 class="text-center">Make an appointment</h1>
    <form method="POST" action="/appointment" id="appointmentform"> 
        @csrf
        <!-- NAME FIELD -->
        <input type="text" name="name" placeholder="Name">
        @error('name') <div style="color:red">{{ $message }}</div> @enderror
        <!-- PHONE FIELD -->
        <input type="tel" name="phone" placeholder="Phone">
        @error('phone') <div style="color:red">{{ $message }}</div> @enderror
        <!-- CONTRACTOR SELECTOR -->
        <select name="contractor">
            <option value="0" selected disabled>Choose the contractor</option>
            <option value="Contractor 1">Contractor 1</option>
            <option value="Contractor 2">Contractor 2</option>
        </select>
        @error('contractor') <div style="color:red">{{ $message }}</div> @enderror
        <!-- DATE FIELD -->
        <input type="text" name="date" id="date" placeholder="Choose the date from calendar" readonly>
        @error('date') <div style="color:red">{{ $message }}</div> @enderror
        <!-- HOUR SELECTOR -->
        <select name="hour">
            <option value="0" selected disabled>Choose hour</option>
            <option value="09">09</option>
            <option value="10">10</option>
            <option value="11">11</option>
            <option value="12">12</option>
            <option value="13">13</option>
            <option value="14">14</option>
            <option value="15">15</option>
            <option value="16">16</option>
            <option value="17">17</option>
        </select>
        @error('hour') <div style="color:red">{{ $message }}</div> @enderror
        <!-- MINUTE SELECTOR -->
        <select name="minute">
            <option value="0" selected disabled>Choose minute</option>
            <option value="00">00</option>
            <option value="05">05</option>
            <option value="10">10</option>
            <option value="15">15</option>
            <option value="20">20</option>
            <option value="25">25</option>
            <option value="30">30</option>
            <option value="35">35</option>
            <option value="40">40</option>
            <option value="45">45</option>
            <option value="50">50</option>
            <option value="55">55</option>
        </select>
        @error('minute') <div style="color:red">{{ $message }}</div> @enderror
        <!-- SERVICES CHECKBOXES -->
        <div class="container d-flex flex-wrap justify-content-around">
            <p id="service-text-1"><input type="checkbox" id="service-1" name="services"> Service 1</p>
            <p id="service-text-2"><input type="checkbox" id="service-2" name="services"> Service 2</p>
            <p id="service-text-3"><input type="checkbox" id="service-3" name="services"> Service 3</p>
            <p id="service-text-4"><input type="checkbox" id="service-4" name="services"> Service 4</p>
            <p id="service-text-5"><input type="checkbox" id="service-5" name="services"> Service 5</p>
            <p id="service-text-6"><input type="checkbox" id="service-6" name="services"> Service 6</p>            
        </div>
        @error('services') <div style="color:red">{{ $message }}</div> @enderror
        <!-- REDIRECT PAGE FIELD -->
        <input type="text" name="page" id="redirectpage" value="client" hidden>
        <!-- SUBMIT BUTTON  -->
        <button type="submit" class="btn btn-primary"id="makeappointment">Make Appointment</button>
    </form>
</div>