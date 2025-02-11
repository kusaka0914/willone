                                @if(isset($office_name) || isset($business))
									<div class="office-name">
										<h1 class="office-name-heading">
                                            <div class="office-name-addr">
                                                {{$addr1_name}}{{$addr2_name}}
                                            </div>
                                            <div class="office-name-title">
                                                @if(isset($office_name))
                                                {{ $office_name }}
                                                @endif
                                                @if(isset($business))
                                                {{ $business }}
                                                @endif
                                            </div>
                                        </h1>
                                        <div class="office-name-data">
                                            <div class="office-name-item">
                                                <div class="office-name-item-heading">
                                                募集職種
                                                </div>
                                                <div class="office-name-item-type">
                                                {{$office_job_type}}
                                                </div>
                                            </div>
                                            <div class="office-name-item">
                                                <div class="office-name-item-heading">
                                                雇用形態
                                                </div>
                                                <div class="office-name-item-type">
                                                {{$office_employment_type}}
                                                </div>
                                            </div>
                                        </div>
                                        <p class="office-name-text">
                                        の詳しい情報をお届けします。
                                        </p>
									</div>
									@endif
