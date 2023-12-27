@extends('backend.layout.admin_master')
@section('title', 'Product List')
@section('content')
	<div class="content-wrapper">
		<div class="content-header row">
			<div class="content-header-left col-md-9 col-12 mb-2">
				<div class="row breadcrumbs-top">
					<div class="col-12">
						<h2 class="content-header-title float-start mb-0">Dashboard</h2>
						<div class="breadcrumb-wrapper">
							<ol class="breadcrumb">
								<li class="breadcrumb-item"><a
										href="{{route(currentUser().'Dashboard')}}">{{ encryptor('decrypt', Session::get('username')) }}</a>
								</li>
								<li class="breadcrumb-item"><a href="#">Food</a></li>
								<li class="breadcrumb-item active">List</li>
							</ol>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="content-body">
			<!-- Responsive tables start -->
			<div class="row" id="table-responsive">
				<div class="col-12">
					<div class="card">
						<div class="card-header">
							<h4 class="card-title">
								All Food List Here...
							</h4>
							<div class="d-flex justify-content-end">
								<a href="{{route(currentUser().'.addNewFoodForm')}}" class="btn btn-primary font-weight-bolder  waves-effect waves-float waves-light">
									<i class="la la-list"></i>New Food
								</a>
							</div>
						</div>
						<div class="card-body">
							<div class="table-responsive">
								<div id="myTable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
									<div class="row">
										<div class="col-sm-12">
											<table class="table table-striped text-center table-bordered dt-responsive dataTable">
												<thead>
													<tr>
														<th>SL.</th>
														<th>Item Name</th>
														<th>Image</th>
														<th>Category</th>
														<th>Price</th>
														<th>Dis price</th>
														<th>Description</th>
														<th>Featured</th>
														<th>Deliverable</th>
														<th>Action</th>
													</tr>
												</thead>

												<tbody>
													@if(count($allFood))
													@foreach($allFood as $index => $food)
													<tr role="row">
														<td>{{++$index}}</td>
														<td>{{$food->name}}</td>
														<td><div class="d-flex flex-column">
                											<span class="fw-bolder mb-25"><img src="{{asset('/')}}storage/app/public/images/food/thumbnail/{{$food->thumbnail}}" alt="" class="img-thumbnail thumb-sm mr-1" width="100px"> </span>
                										</div></td>
														<td>@if($food->category){{$food->category->name}}@endif</td>
														<td>{{$food->price}}</td>
														<td>{{$food->discount_price}}</td>
														<td>{{$food->description}}</td>
														<td>
													
															@if($food->featured == 1)
                											<span class="badge rounded-pill badge-light-primary me-1">Active</span>
                											@else
                											<span class="badge rounded-pill badge-light-danger me-1">Inactive</span>
                											@endif
														</td>
														<td>
														@if($food->deliverable == 1)
                											<span class="badge rounded-pill badge-light-primary me-1">Active</span>
                											@else
                											<span class="badge rounded-pill badge-light-danger me-1">Inactive</span>
                											@endif
														

														</td>
														<td>
                                                            <div class="dropdown">
                                                                <button type="button" class="btn btn-sm dropdown-toggle hide-arrow waves-effect waves-float waves-light" data-bs-toggle="dropdown" aria-expanded="false">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-vertical"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg>
                                                                </button>
                                                                <div class="dropdown-menu" style="">
                                                                    <a class="dropdown-item" href="{{route(currentUser().'.editFood',[encryptor('encrypt', $food->id)])}}">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 me-50"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg>
                                                                        <span>Edit</span>
                                                                    </a>
                                                                    <a class="dropdown-item" href="{{route(currentUser().'.deleteFood', [encryptor('encrypt', $food->id)])}}">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash me-50"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                                                        <span>Delete</span>
                                                                    </a>
                                                                </div>
                                                            </div>
										                </td>
										            </tr>
            										@endforeach
            										@endif
            									</tbody>
    										</table>
    										{{$allFood->links()}}
    									</div>
    								</div>
    							</div>
    						</div>
    					</div>
    				</div>
    			</div>
    		</div>
    		<!-- Responsive tables end -->
    	</div>
	</div>
	@endsection
	@push('scripts')
	<script>
		$(document).ready(function () {
			$('#myTable').DataTable();
		});
	</script>
	@endpush