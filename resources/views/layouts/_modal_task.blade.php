<div id="task" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Create Task</h4>
            </div>
            <div class="modal-body">
                <div class="scroller" style="height:430px" data-always-visible="1" data-rail-visible1="1">

                    <form class="form-horizontal">

                        <div class="row">
                            <div class="col-md-12">
                                <label class="control-label">Task Title</label>
                                <input type="text" class="form-control" placeholder="Enter Task Title">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <label class="control-label">Assigned To</label>
                                <select class="form-control select2-multiple" multiple>
                                    <option value="1995">Alrick Davis</option>
                                    <option value="1993">Christopher Green</option>
                                    <option value="3">Daren Daly</option>
                                    <option value="873">David Mcgraw</option>
                                    <option value="1024">DEMO Field Worker</option>
                                    <option value="1023">DEMO Manager Job Site</option>
                                    <option value="1999">Diego Mejia</option>
                                    <option value="1998">Enrique Lara</option>
                                    <option value="1994">Fidelmar Cornejo</option>
                                    <option value="1991">Francisco Arellano</option>
                                    <option value="2959">Gasper LoMonaco</option>
                                    <option value="3635">Herb Trevathan</option>
                                    <option value="3636">Herb Trevathan</option>
                                    <option value="1997">Jesus Guerrero</option>
                                    <option value="2000">Jonathan Mejia</option>
                                    <option value="2001">Jose Montenegro</option>
                                    <option value="2002">Juan Montenegro</option>
                                    <option value="2006">Juan Valle Valle</option>
                                    <option value="684">Karina Rada</option>
                                    <option value="10">Keith Daly</option>
                                    <option value="3022">Linda Thorsen</option>
                                    <option value="1025">Mary Jo Villalobos</option>
                                    <option value="12">Nigel  Burton</option>
                                    <option value="3927">Patrick Daly</option>
                                    <option value="2005">Prisco Sanchez</option>
                                    <option value="1996">Robert Goodwin</option>
                                    <option value="9">Robert Holland</option>
                                    <option value="675">Ruben Montenegro</option>
                                    <option value="2003">Ruben Montengro, Sr.</option>
                                    <option value="13">Sammy  Maldoando</option>
                                    <option value="6">Silvana McLean</option>
                                    <option value="903">Steven Devito</option>
                                    <option value="2672">Steven DeVito Sr.</option>
                                    <option value="5">Steven Joseph</option>
                                    <option value="2396">Tim  Maxwell</option>
                                    <option value="3950" selected="selected">Tom Siodlak</option>
                                    <option value="677">Victor Maldonado</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="control-label">Date Due</label>
                                <div class="input-group date form_meridian_datetime">
                                    <input type="text" size="16" class="form-control">
                                    <span class="input-group-btn">
                                            <button class="btn default date-set" type="button">
                                                <i class="fa fa-calendar"></i>
                                            </button>
                                        </span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <label class="control-label">Proposal</label>
                                <input type="text" class="form-control" placeholder="Enter Proposal ID Number">
                            </div>
                            <div class="col-md-6">
                                <label class="control-label">Workorder</label>
                                <input type="text" class="form-control" placeholder="Enter Workorder ID Number">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <label class="control-label">Priority</label>
                                <select class="form-control">
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="control-label">File Upload</label>
                                <input type="file" id="exampleInputFile">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">

                                <label class="control-label">Task Details</label>
                                <textarea class="form-control" rows="5"></textarea>

                            </div>
                        </div>

                    </form>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn dark btn-outline">Cancel</button>
                <button type="button" class="btn green">Create Task</button>
            </div>
        </div>
    </div>
</div>