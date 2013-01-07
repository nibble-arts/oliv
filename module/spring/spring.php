<?PHP
//
// OLIV
//
// This file is part of the OLIV Content Management System.
//
// Copyright(c) 2012 Thomas H Winkler
// thomas.winkler@iggmp.net
//
// This file may be licensed under the terms of of the
// GNU General Public License Version 3 (the ``GPL'').
//
// Software distributed under the License is distributed
// on an ``AS IS'' basis, WITHOUT WARRANTY OF ANY KIND, either
// express or implied. See the GPL for the specific language
// governing rights and limitations.
//
// You should have received a copy of the GPL along with this
// program. If not, go to http://www.gnu.org/licenses/gpl.html
// or write to the Free Software Foundation, Inc.,
// 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
//

//------------------------------------------------------------------------------
// Compound spring calculator module
//
// Version 0.1
//------------------------------------------------------------------------------

if (!system::OLIVCORE()) die ("mod_search::search.php - OLIVCore not present");
if (!system::OLIVINDEX()) die ("mod_search::search.php - OLIVIndext not present");
if (!system::OLIVERROR()) die ("mod_search::search.php - OLIVError not present");

class spring extends OLIVModule
{
  public function __construct($header)
  {
		$this->template = OLIVModule::load_template($header);
		$this->content = OLIVModule::load_content($header);

		$this->calculate();
  }


//------------------------------------------------------------------------------
	private function calculate()
	{
		$mand = 0;

		if(argv::string_abort())
		{
			argv::remove("spring_L");
			argv::remove("spring_b");
			argv::remove("spring_t");
			argv::remove("spring_n");
			argv::remove("spring_n1");
			argv::remove("spring_F");
			argv::remove("spring_s");
			argv::remove("spring_type");
		}

		$L = argv::spring_L();
		$b = argv::spring_b();
		$t = argv::spring_t();
		$n = argv::spring_n();
		$n1 = argv::spring_n1();
		$F = argv::spring_F();
		$s = argv::spring_s();
		$type = argv::spring_type();

		$F = $F/2;
		$L = $L/2;
		$E = 200000;


// set mandatory fields
		$this->content->leaf_length_class = "mand";
		$this->content->leaf_width_class = "mand";
		$this->content->leaf_thickness_class = "mand";
		$this->content->leaf_count_class = "mand";


// calculation started
		if (argv::spring_calc())
		{
			$this->content->action = "calculate";

			
// check mandatory fields
			if (!$L)
				$this->content->leaf_length_class = "mand_missing";
			else
				$mand++;

			if (!$b)
				$this->content->leaf_width_class = "mand_missing";
			else
				$mand++;

			if (!$t)
				$this->content->leaf_thickness_class = "mand_missing";
			else
				$mand++;

			if (!$n)
				$this->content->leaf_count_class = "mand_missing";
			else
				$mand++;
		}


// set result values
		$this->content->result = "---";
		$this->content->spring_result_class = "spring_result_error";


// set result type
		if ($F)
		{
			$this->content->result_value = "s";
			$this->content->result_unit = "mm";
			$this->text("result_name","bending");
			$this->content->spring_result_class = "spring_result";
		}
		if ($s)
		{
			$this->content->result_value = "F";
			$this->content->result_unit = "N";
			$this->text("result_name","force");
			$this->content->spring_result_class = "spring_result";
		}


// abbort if not mandatory
		if ($mand < 4)
		{
			return FALSE;
		}


// PSI
		$psi = 3 / (2 + (($n1 + $type) / $n));


//------------------------------------------------------------------------------
// calculate bending
		if ($F)
		{
// s denominator
			$_s = $E * $n * $b * pow($t,3);

// if s not NULL -> calculate
			if ($_s)
				$s = ($psi * 4 * $F * pow($L,3)) / $_s;

			$this->content->result = number_format($s,3);
		}

//------------------------------------------------------------------------------
// calculate force
		elseif ($s)
		{
// F denominator
			$_F = 4 * pow($L,3) * $psi;

// if F not NULL -> calculate
			if ($_F)
				$F = ($s * $E * $n * $b * pow($t,3)) / $_F;

			$this->content->result = number_format($F,3);
		}

// sigma
		$this->content->spring_sigma = number_format((6 * $F * $L) / ($n * $b * pow($t,2)));
//		echoall($this->content);
	}
}


?>
