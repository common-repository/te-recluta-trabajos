<?php
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'TeReclutaValidations.php';

class TeReclutaJobs
{
  private string $plugin_name;
  private string $version;
  protected string $company_code;
  protected int $pagination;
  protected int $time;
  protected int $description;
  protected int $location;
  protected int $page;
  protected int $pages;
  protected array $allowed_html = [
    'a' => [
      'class' => [],
      'href' => [],
      'title' => [],
      'target' => []
    ],
    'div' => [
      'class' => []
    ],
    'p' => [],
    'br' => [],
    'hr' => [],
    'em' => [],
    'u' => [],
    'i' => [],
    'b' => [],
    'strong' => [],
    'blockquote' => [],
    'h1' => [],
    'h2' => [],
    'h3' => [],
    'ul' => [],
    'ol' => [],
    'li' => [
      'class' => []
    ]
  ];
  protected string $te_recluta_url = 'https://panel.terecluta.com/api/jobs/jobs/company_code?page=jobs_page';
  protected TeReclutaValidations $validations;

  public function __construct(string $plugin_name, string $version, $shortcode_attributes)
  {
    $this->plugin_name = $plugin_name;
    $this->version     = $version;

    $te_recluta_options = get_option( 'te_recluta_option_name' );

    $this->company_code = (isset($te_recluta_options['te_recluta_company_code'])) ? $te_recluta_options['te_recluta_company_code'] : "";

    $attributes = shortcode_atts([
			'pagination' => '0',
			'time' => '1',
			'description' => '1',
			'location' => '1'
    ], $shortcode_attributes);

    $pagination = esc_attr($attributes['pagination']);
    $time = esc_attr($attributes['time']);
    $description = esc_attr($attributes['description']);
    $location = esc_attr($attributes['location']);

    $this->pagination = ($pagination == 1) ? 1 : 0;
    $this->time = ($time == 1) ? 1 : 0;
    $this->description = ($description == 1) ? 1 : 0;
    $this->location = ($location == 1) ? 1 : 0;

    $this->page = (intval(@$_GET['te_recluta_page']) <= 0) ? 1 : intval(@$_GET['te_recluta_page']);
    $this->pages = 0;

    $this->te_recluta_url = str_replace("company_code", $this->company_code, $this->te_recluta_url);
    $this->te_recluta_url = str_replace("jobs_page", $this->page, $this->te_recluta_url);

    $this->validations = new TeReclutaValidations($this->plugin_name, $this->version);
  }

  public function renderTeReclutaJobs(): void
  {
    if ($this->validations->hasErrors()) {
      $this->validations->renderErrors();
      return;
    }

    $jobs = $this->TeReclutaJobs();

    if (!$jobs || empty($jobs)) {
      esc_html__('Trabajos no encontrados', 'te-recluta');

      return;
    }

    $this->renderList($jobs);
  }
  
  public function renderList(array $jobs): void
  {
		echo '<div class="te-recluta-jobs">';

	    foreach ($jobs as $row) {
				echo '<div class="te-recluta-jobs__item">
					<a href="' . esc_url($this->getURL($row["domain_jobs"], $row["slug"])) . '" target="_blank">';

						if($this->time == '1'){
							echo ' <div class="te-recluta-jobs__time">' .  esc_html(te_recluta_print_time($row["created_date"])) . '</div>';
						}
						
						echo '<div class="te-recluta-jobs__title">' .  esc_html(te_recluta_hide_contact($row["camp"])) .' | ' .  esc_html($row["company"]) . '</div>';

						if($this->description == '1'){
							echo '<div class="te-recluta-jobs__text"><p>' .  wp_kses(nl2br(te_recluta_hide_contact($row["short_description"])), $this->allowed_html) . '</p></div>';
						}

						if($this->location == '1'){
							echo '<div class="te-recluta-jobs__discrict">' .   esc_html(@$row["country"]) . ', <span>' .  esc_html($row["state"]) . '</span></div>';
						}
		            echo '</a>
		        </div>';
	        }

	    echo '</div>';

   	if($this->pagination == '1' && $this->pages > 1){
    	echo wp_kses($this->getPagination(), $this->allowed_html);
    }
  }

  public function getURL(string $domain_jobs, string $slug): string
  {
  	return  'https://' .  $domain_jobs . '/job.php?job=' . $slug . '&source=wp_plugin';
  }

  public function getPagination(): string
  {
    $url = preg_replace('/(.*)(?|&)te_recluta_page=[^&]+?(&)(.*)/i', '$1$2$4', $url .'&');
		$url = substr($url, 0, -1);
		
		if (strpos($url, '?') === false) {
			$url = $url .'?te_recluta_page='. $value;
		} else {
			$url = $url .'&te_recluta_page='. $value;
		}

		$html = '<div class="te-recluta-pagination"><ul>';

		if($this->pages > 0){
		        $show = 4;
		        $start = $this->page - round($show / 2);
		        
		        // Calcular final
		        $finish = $start + $show;
		        if($finish > $this->page) {
		            $finish = $this->page + $show; 
		            $start = $finish - ($show * 2);
		        }

		        if($start < 1) {
		            $start = 1;
		        }

		        if($finish > $this->pages) {
		            $finish = $this->pages;
		        }
		         
		        if($this->page > 1) {
		        	$html.= '<li><a href="' .  $url . ' ' .  ($this->page - 1) . '">←</a></li>';
		        }

		        if($start > 1) {
		        	$html.= '<li>...</li>';
		        }

		        for($i = $start; $i <= $finish; $i++) {
		                $active = ($this->page == $i) ? 'active' : '';
		        	$html.= '<li class="' .  $active . '"><a href="' . $url . $i . '">' . $i . '</a></li>';
		        }

		        if($finish < $this->pages) {
		        	$html.= '<li>...</li>';
		        }

		        if($this->page < $this->pages) {
		        	$html.= '<li><a href="' .  $url . ' ' .  ($this->page + 1) . '">→</a></li>';
		        }
		         
		}	

		$html.= '</ul></div>';

		return $html;
  }

  public function TeReclutaJobs(): array
  {
    $response = wp_remote_get(esc_url($this->te_recluta_url));
    $json = (array) json_decode(wp_remote_retrieve_body($response), true);

    if ($json && array_key_exists("status", $json)) {
      return $json['data'];
    }

    return false;
  }
}
