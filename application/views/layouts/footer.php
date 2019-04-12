            </div>
            <div class="sidebar-content"><?php $this->load->view('layouts/sidebar'); ?></div>
        </div>
    </div> <!-- end main-wrapper -->
</div> <!-- end unmark-wrapper -->
</main> <!-- end unmark-stage -->

<?php $this->load->view('layouts/footer_scripts')?>

<!-- Test Tag Module -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js"></script>
<script>
  $(document).ready(function() {
    $('#input-tags').selectize({
        delimiter: ',',
        persist: false,
        create: function(input) {
            return {
                value: input,
                text: input
            }
        }
    });
  });
</script>

</body>
</html>
