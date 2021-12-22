return `<div class="card bg-white rounded mb-3">
                          <div class="card-body ">
                            <div class="col-md-12 col-sm-12">
                                <div class="row">
                                    <div class="col-md-6 col-sm-6">
                                        <a
                                            class="nav-link-cs"
                                            href="#referal"
                                            data-toggle="collapse"
                                            data-target="#referal${
                                                m.province_id
                                            }"
                                            style="color: #000000; text-decoration:none"
                                            >
                                            ${m.province} 
                                        </a>
                                    </div>
                                    <div class="col-md-4 col-sm-4">
                                        Target : ${m.target} 
                                    </div>
                                    
                                </div>
      
                                          <div class="collapse" id="referal${
                                              m.province_id
                                          }" aria-expanded="false">
                                          ${m.regencies.map(
                                              (reg) =>
                                                  `<div class="card-body">
                                                    <div class="col-md-12 col-sm-12">
                                                    <div class="row border-bottom">
                                                      <div class="col-md-7 col-sm-7">
                                                          <a  class="nav-link-cs " 
                                                              href="#referalregs"
                                                             
                                                              data-target="#referalregs${
                                                                  reg.id
                                                              }" 
                                                              style="color: #000000; text-decoration:none">
                                                              ${reg.name}
                                                          </a>
                                                      </div>
                                                      <div class="col-md-3 col-sm-3">
                                                         Target : ${reg.target}
                                                      </div>
                                                    </div>
                                                        <div class="" id="#referalregs${
                                                            reg.id
                                                        }" aria-expanded="false">
                                                        ${reg.districts.map(
                                                            (dist) =>
                                                                `
                                                                <div class="card-body">
                                                                     <div class="col-md-12 col-sm-12">
                                                                        <div class="row border-bottom">
                                                                            <div class="col-md-8 col-sm-8">
                                                                                <a  class="nav-link-cs " 
                                                                                    href="#referalreg" 
                                                                                    data-target="#referalreg${
                                                                                        dist.id
                                                                                    }" 
                                                                                    style="color: #000000; text-decoration:none">
                                                                                    KEC. ${
                                                                                        dist.name
                                                                                    }
                                                                                </a>
                                                                            </div>
                                                                            <div class="col-md-3 col-sm-3">
                                                                                    Target : ${
                                                                                        dist.target
                                                                                    }
                                                                            </div>
                                                                        </div>
                                                                        <div class=""  aria-expanded="false">
                                                                        ${dist.villages.map(
                                                                            (
                                                                                vill
                                                                            ) =>
                                                                                ` <div class="card-body shadow">
                                                                                    <div class="col-md-12 col-sm-12">
                                                                                        <div class="row border-bottom">
                                                                                            <div class="col-md-9 col-sm-9">
                                                                                                <a  class="nav-link-cs " 
                                                                                                    href="#referalreg" 
                                                                                                    data-target="#referalreg${vill.id}" 
                                                                                                    style="color: #000000; text-decoration:none">
                                                                                                    Ds. ${vill.name}
                                                                                                </a>
                                                                                            </div>
                                                                                            <div class="col-md-3 col-sm-3">
                                                                                                    Target : ${vill.target}
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                  </div>
                                                                                `
                                                                        )}
                                                                        </div>
                                                                     </div>
                                                                </div>
                                                                `
                                                        )}
                                                        </div>
                                                    </div>
                                                  </div>
                                                  `
                                          )}
                                          </div>
                                      </div>
                                  </div>
                              </div>
            `;
