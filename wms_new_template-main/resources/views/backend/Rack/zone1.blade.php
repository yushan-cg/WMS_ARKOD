<!--Zone1-->
<h2 style="text-align: center">ZONE 1</h2>
<div class="zone-container">
    <div id="zone1" class="grid-container-zone1">
        <?php $data = true; ?> <!-- Initialize $data outside the loop -->
        @foreach(range(1, 21) as $row)
        @foreach(range('A', 'F') as $column)
        @for($i = 22; $i >= 1; $i--)
            @if($row == (22 - $i))
                <?php $rack_row = 0 + $i; ?>
            @endif
        @endfor
        <?php
        $data = DB::table('location')->where('LocationCode', '=', $column . $rack_row)->first();
        $full = false;
        $partial = false;

        if($data) {
            if($data->Occupied >= $data->Capacity) {
                $full = true;
            } else {
                $partial = true;
            }
        }
    ?>
    <?php $found = false; ?>


        <div class="section-box @if($full ) bg-full @elseif($partial) bg-partial @else bg-empty @endif">

            <a href="#">
                <h4 class="text-white my-0">
                    @for($i = 22; $i >= 1; $i--)
                        @if($row == (22 - $i))
                        <?php $rack_row = 0 + $i; ?>
                            {{ $column }}{{ $rack_row }}
                        @endif
                    @endfor
                </h4>
                <div class="box-dec">
                    <div class="section-rack d-flex align-items-center" @if($full ) style="background-color:rgba(255, 140, 0, 0.9)" @elseif($partial) style="background-color: rgba(0, 135, 90, 0.9);" @else style="background-color: #A8A8A8"  @endif>
                        <div class="box-img">
                            <img src="{{ asset('assets/images/box.png') }}" class="img-fluid" alt="" />
                        </div>
                        <div class="dec">
                            <h4 class="text-white my-0">



                                @for($i = 22; $i >= 1; $i--)
                                    @if($row == (22 - $i))
                                        {{ $column }}{{ $rack_row }}

                                        @if($data)
                                            @if($data->LocationType == 1)
                                                <?php $LocationTypeName = "Floor";
                                                $content = $data->Occupied . '/' . $data->Capacity;
                                                ?>
                                            @endif

                                            {{ $LocationTypeName }}
                                            {{ $content }}
                                            <?php $found = true; ?> {{-- Set $found to true --}}


                                        @endif
                                    @endif
                                @endfor

                                {{-- If data not found --}}
                                @if(!$found)
                                    Empty
                                @endif
                            </h4>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        @endforeach
        @endforeach
    </div>
</div>
