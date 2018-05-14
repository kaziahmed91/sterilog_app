<div class="modal fade" id="activeTestModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Update Spore Test</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        
            <div class="modal-body">
                <div class=''>
                    <div class="row switchRow spore-slide">
                        <p>Control Vial Sterile</p>
                        <label class="switch switch-flat">
                            <input class="switch-input" id="control_sterile" type="checkbox" />
                            <span class="switch-label" data-on="Sterile" data-off="Unsterile"></span> 
                            <span class="switch-handle"></span> 
                        </label>
                    </div>
                    
                    <div class="row switchRow spore-slide">
                        <p>Test Vial Sterile</p>
                        <label class="switch switch-flat">
                            <input class="switch-input" id="test_sterile" type="checkbox" />
                            <span class="switch-label" data-on="Sterile" data-off="Unsterile"></span> 
                            <span class="switch-handle"></span> 
                        </label>
                    </div>
                    <div class="form-group">
                        <label for="additional_comments">Additional Comments</label>
                        <textarea class='form-control' id="additional_comments_before" name="additional_comments"  rows="2" ></textarea>
                    </div>
                </div>
            </div>
    
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="closeUpdateScoreTestModal" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="updateSporeTest">Save changes</button>
            </div>

        </div>
    </div>
</div>