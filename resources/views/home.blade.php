@extends('layouts.app')

@section('header')
    <link rel="stylesheet" href="/css/style.css">
@endsection

@section('content')
<div class="container">
  <div class="panel panel-default">
    <div class="panel-heading"><h2>Latest</h2></div>
    <div class="panel-body container">
      @if($latest_file <> '')
        <div class="w3-card-4 col-md-3 col-md-offset-1 col-sm-4 col-sm-offset-1 col-xs-12" style="padding: 50px 25px;">
          <div class="w3-container">
            <h4><center><b>{{$latest_file->FileTitle}}</b></center></h4><br /><br /><br />
            <p><b>{{$latest_file->Authors}}</b></p> 
            <p>{{$latest_file->thesis_date->format('F j, Y')}}</p>
          </div>
          <center class="w3-container" style="padding: 10px">
            <span class="glyphicon glyphicon-eye-open">
              {{ DB::table('recent_views')->where('file_id',$latest_file->id)->pluck('user_id')->count() }}
            </span>
            <span class="glyphicon glyphicon-star-empty">
              {{ DB::table('favorites')->where('file_id',$latest_file->id)->pluck('user_id')->count() }}
            </span>
            <br />
          </center>
        </div>
        <div class="col-md-1 col-sm-1">
        </div>
        <div class="col-md-5 col-sm-5">
          <h1>{{$latest_file->FileTitle}}</h1>
          <p style="height: 7.5em; overflow: hidden;">{{$latest_file->Abstract}}</p>
          @if(Request::server('SERVER_NAME') <> '127.0.0.1')
            <a href="/pdf.js/web/viewer.html?file=http://files/{{ Request::server('SERVER_NAME').$latest_file->FilePath }}" target="_blank">
          @else
            <a href="/pdf.js/web/viewer.html?file=http://localhost:8000/files/{{$latest_file->FilePath }}" target="_blank">
          @endif
              <button type="button" class="btn btn-info col-sm-offset-1 col-xs-offset-3" >View more</button>
            </a>
        </div>
      @else
        <h2>No Records Found.</h2>
      @endif
    </div>
    {{-- <div class="panel-footer">
    </div> --}}
  </div>
<div class="container">
  <ul class="nav nav-pills">
    <li class="active"><a data-toggle="tab" href="#latest">Latest</a></li>
    <li><a data-toggle="tab" href="#suggested">Suggested</a></li>
    <li><a data-toggle="tab" href="#mostviewed">Most Viewed</a></li>
  </ul>
  <div class="tab-content">
    <div id="latest" class="tab-pane fade in active">
      <div class="container">
        <h2>Latest</h2>
        <div class="table-responsive">          
          <table class="table">
            <thead>
              <tr>
                <th></th>
                <th></th>
                <th><span class="glyphicon glyphicon-sort-by-order"></span></th>
                <th>Title</th>
                <th>Category</th>
                <th>Author/s</th>
                <th>Course</th>
                <th>Adviser</th>
                <th>Thesis Date</th>
                <th><span class="glyphicon glyphicon-eye-open"></span></th>
                <th><span class="glyphicon glyphicon-star-empty"></span></th>
              </tr>
            </thead>
            <tbody>
              @include('file.table-contents')
            </tbody>
          </table>
          {{-- {{$files->links)}} --}}
        <br />
        </div>
        <center>
          <button type="button" class="btn btn-info">View more</button>
        </center>
      </div>
    </div>
    <div id="suggested" class="tab-pane fade">
      <div class="container">
        <h2>Suggested</h2>                                                                                 
        <div class="table-responsive">          
          <table class="table">
            <thead>
              <tr>
                <th></th>
                <th></th>
                <th><span class="glyphicon glyphicon-sort-by-order"></span></th>
                <th>Title</th>
                <th>Category</th>
                <th>Author/s</th>
                <th>Course</th>
                <th>Adviser</th>
                <th>Thesis Date</th>
                <th><span class="glyphicon glyphicon-eye-open"></span></th>
                <th><span class="glyphicon glyphicon-star-empty"></span></th>
              </tr>
            </thead>
            <tbody>
              <?php $no=1; ?>
              <p class="QRCode hidden">
                @if(Request::server('SERVER_NAME') <> '127.0.0.1')
                  <?php 
                    $path='http://'.Request::server('SERVER_NAME').'/pdf.js/web/viewer.html?file=http://'.Request::server('SERVER_NAME')."/files/";
                    echo $path;
                  ?>
                @else
                  <?php 
                    $path='http://localhost:8000/pdf.js/web/viewer.html?file=http://localhost:8000/files/';
                    echo $path;
                  ?>
                @endif
              </p>
              @foreach($suggested_files as $file)
                <tr>
                  @if(Auth::user()->Role <> 'Admin')
                    {{-- <td> --}}
                      <!-- Button trigger modal -->
                      {{-- <button class="openModal" data-toggle="modal" data-target="#myModal">
                        <i class="fa fa-sticky-note-o" aria-hidden="true"></i>
                      </button> --}}
                      {{-- @if(in_array($file->id,$notes))
                        <form action="/editNotes" method="POST">
                          {{ csrf_field() }}
                          {{method_field('PATCH')}}


                        </form> --}}
                    {{-- </td> --}}
                    <td>
                        <form action="/bookmark" method="POST">
                          {{ csrf_field() }}
                          <input type="hidden" name="file_id" value="{{$file->id}}">
                          @if(in_array($file->id, $bookmarks))
                            <button class="not-book" type="submit" id="favorite">
                              <i  class="fa fa-bookmark" aria-hidden="true"></i>
                            </button>
                          @else
                            <button class="btn-book" type="submit" id="favorite">
                              <i  class="fa fa-bookmark-o" aria-hidden="true"></i>
                            </button>
                          @endif
                        </form>
                    </td>
                    <td>
                        <form action="/favorite" method="POST">
                          {{ csrf_field() }}
                          <input type="hidden" name="file_id" value="{{$file->id}}">
                          @if(in_array($file->id, $favorites))
                            <button class="not-fav" type="submit" id="favorite">
                              <i  class="fa fa-star" aria-hidden="true"></i>
                            </button>
                          @else
                            <button class="btn-fav" type="submit" id="favorite">
                              <i  class="fa fa-star-o" aria-hidden="true"></i>
                            </button>
                          @endif
                        </form>
                    </td>
                  @endif
                  <td>{{$no++}}</td>
                  <td class="FileTitle">
                    <!-- Button trigger modal -->
                    <a class="btn viewInfo" data-toggle="modal" data-target="#myModal" data-id="{{$file->id}}" data-title="{{$file->FileTitle}}" data-abstract="{{$file->Abstract}}" data-path="{{$file->FilePath}}">
                      {{$file->FileTitle}}
                    </a>
                    {{-- <p class="fileAbstract"></p> --}}
                    {{-- (Background statement) The spread of antibiotic resistance is aided by mobile elements such as transposons and conjugative plasmids. (Narrowing statement) Recently, integrons have been recognised as genetic elements that have the capacity to contribute to the spread of resistance. (Elaboration of narrowing) (statement) Integrons constitute an efficient means of capturing gene cassettes and allow expression of encoded resistance. (Aims) The aims of this study were to screen clinical isolates for integrons, characterise gene cassettes and extended spectrum b-lactamase (ESBL) genes.  (Extended aim) Subsequent to this, genetic linkage between ESBL genes and gentamicin resistance was investigated.  (Results) In this study, 41 % of multiple antibiotic resistant bacteria and 79 % of extended-spectrum b-lactamase producing organisms were found to carry either one or two integrons, as detected by PCR.  (Results)  A novel gene cassette contained within an integron was identified from Stenotrophomonas maltophilia, encoding a protein that belongs to the small multidrug resistance (SMR) family of transporters. (Results)  pLJ1, a transferable plasmid that was present in 86 % of the extended-spectrum b-lactamase producing collection, was found to harbour an integron carrying aadB, a gene cassette for gentamicin, kanamycin and tobramycin resistance and a blaSHV-12 gene for third generation cephalosporin resistance. (Justification of results) The presence of this plasmid accounts for the gentamicin resistance phenotype that is often associated with organisms displaying an extended-spectrum b-lactamase phenotype. --}}

                    <!-- Modal -->
                    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel"></h4>
                          </div>
                          <div class="modal-body">
                            <h3><b>Abstract</b></h3>
                            <p>
                              &nbsp;&nbsp;&nbsp;&nbsp;
                              <span class="abstract"></span>  
                            </p>
                            <p>
                              Read the whole documentation 
                              <a href="" target="_blank" id="file_link" file_id="" onclick="$.get( '/increment_views', { 'file_id': $('#file_link').attr('file_id')});">
                                here.
                              </a>
                            </p>
                            <br>
                            <p class="qrcodeCanvas" style="text-align: center;"></p>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </td>
                  <td>{{$file->Category}}</td>
                  <td>{{$file->Authors}}</td>
                  <td>{{$file->Course}}</td>
                  <td>{{$file->Adviser}}</td>
                  <td>{{$file->thesis_date->format('F j, Y')}}</td>
                  @if(Auth::user()->Role == 'Admin')
                    <td>{{ $file->Status }}</td>
                  @endif
                  <td>
                    {{ DB::table('recent_views')->where('file_id',$file->id)->pluck('user_id')->count() }}
                  </td>
                  <td>
                    {{ DB::table('favorites')->where('file_id',$file->id)->pluck('user_id')->count() }}
                  </td>
                  @if(Auth::user()->Role == 'Admin')
                    <td>
                      @if($file->Status == 'Inactive')
                        <form action="/unlock" method="POST">
                          {{method_field('PATCH')}}
                          {{csrf_field()}}
                          <input type="hidden" name="file_id" value="{{$file->id}}">
                          <button class="btn btn-primary" type="submit"><span class="fa fa-unlock-alt"></span></button>
                      @else
                        <form action="/lock" method="POST">
                          {{method_field('PATCH')}}
                          {{csrf_field()}}
                          <input type="hidden" name="file_id" value="{{$file->id}}">
                          <button class="btn btn-primary" type="submit"><span class="fa fa-lock"></span></button>
                      @endif
                        </form>
                    </td>
                  @endif
                </tr>
              @endforeach
            </tbody>
            </table>
          <br />
          <center>
            <button type="button" class="btn btn-info">View more</button>
          </center>
        </div>
      </div>
    </div>
    <div id="mostviewed" class="tab-pane fade">
      <div class="container">
        <h2>Most Viewed</h2>                                                                        
        <div class="table-responsive">          
          <table class="table">
            <thead>
              <tr>
                <th></th>
                <th></th>
                <th><span class="glyphicon glyphicon-sort-by-order"></span></th>
                <th>Title</th>
                <th>Category</th>
                <th>Author/s</th>
                <th>Course</th>
                <th>Adviser</th>
                <th>Thesis Date</th>
                <th><span class="glyphicon glyphicon-eye-open"></span></th>
                <th><span class="glyphicon glyphicon-star-empty"></span></th>
              </tr>
            </thead>
            <tbody>
              <?php $no=1; ?>
              <p class="QRCode hidden">
                @if(Request::server('SERVER_NAME') <> '127.0.0.1')
                  <?php 
                  $path='http://'.Request::server('SERVER_NAME').'/pdf.js/web/viewer.html?file=http://'.Request::server('SERVER_NAME')."/files/";
                  echo $path;
                  ?>
                @else
                  <?php 
                  $path='http://localhost:8000/pdf.js/web/viewer.html?file=http://localhost:8000/files/';
                  echo $path;
                  ?>
                @endif
              </p>
              @foreach($most_viewed as $file)
                <tr>
                  @if(Auth::user()->Role <> 'Admin')
                    {{-- <td> --}}
                    <!-- Button trigger modal -->
                    {{-- <button class="openModal" data-toggle="modal" data-target="#myModal">
                      <i class="fa fa-sticky-note-o" aria-hidden="true"></i>
                    </button> --}}
                    {{-- @if(in_array($file->id,$notes))
                      <form action="/editNotes" method="POST">
                        {{ csrf_field() }}
                        {{method_field('PATCH')}}

                    </form> --}}
                    {{-- </td> --}}
                    <td>
                      <form action="/bookmark" method="POST">
                        {{ csrf_field() }}
                        <input type="hidden" name="file_id" value="{{$file->id}}">
                        @if(in_array($file->id, $bookmarks))
                        <button class="not-book" type="submit" id="favorite">
                          <i  class="fa fa-bookmark" aria-hidden="true"></i>
                        </button>
                        @else
                        <button class="btn-book" type="submit" id="favorite">
                          <i  class="fa fa-bookmark-o" aria-hidden="true"></i>
                        </button>
                        @endif
                      </form>
                    </td>
                    <td>
                      <form action="/favorite" method="POST">
                        {{ csrf_field() }}
                        <input type="hidden" name="file_id" value="{{$file->id}}">
                        @if(in_array($file->id, $favorites))
                        <button class="not-fav" type="submit" id="favorite">
                          <i  class="fa fa-star" aria-hidden="true"></i>
                        </button>
                        @else
                        <button class="btn-fav" type="submit" id="favorite">
                          <i  class="fa fa-star-o" aria-hidden="true"></i>
                        </button>
                        @endif
                      </form>
                    </td>
                  @endif
                  <td>{{$no++}}</td>
                  <td>{{$file->FileTitle}}</td>
                  <td>{{$file->Category}}</td>
                  <td>{{$file->Authors}}</td>
                  <td>{{$file->Course}}</td>
                  <td>{{$file->Adviser}}</td>
                  <td>{{$file->thesis_date}}</td>
                  <td>{{ $file->NumberOfViews }}</td>
                  <td>
                    {{ DB::table('favorites')->where('file_id',$file->id)->pluck('user_id')->count() }}
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
          {{-- {{$most_viewed->links()}} --}}
          <br />
          <center>
            <button type="button" class="btn btn-info">View more</button>
          </center>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection